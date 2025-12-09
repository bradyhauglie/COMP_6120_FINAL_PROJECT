<?php
require_once 'db_config.php';

$messages = [];
$resultsHtml = '';
$querySubmitted = false;

function connect() {
    return getDBConnection();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reset'])) {
    $querySubmitted = false;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $querySubmitted = true;
    try {
        $conn = connect();
        $query = trim($_POST['query'] ?? '');

        if ($query === '') {
            $messages[] = ['type' => 'error', 'text' => 'Please enter a SQL query.'];
        } elseif (stripos($query, 'DROP') !== false) {
            $messages[] = ['type' => 'error', 'text' => 'DROP statements are not allowed.'];
        } else {
            $res = $conn->query($query);

            if ($res === false) {
                $messages[] = ['type' => 'error', 'text' => $conn->error];
            } elseif ($res === true) {
                $affected = $conn->affected_rows;
                $messages[] = ['type' => 'success', 'text' => 'Query executed. Affected rows: ' . $affected];
            } else {
                $rows = [];
                while ($r = $res->fetch_assoc()) {
                    $rows[] = $r;
                }
                $res->free();

                if (count($rows) > 0) {
                    $resultsHtml .= '<div class="results-container">';
                    $resultsHtml .= '<div class="message info">Query returned ' . count($rows) . ' row(s)</div>';
                    $resultsHtml .= '<table>';
                    $resultsHtml .= '<tr>';
                    foreach (array_keys($rows[0]) as $col) {
                        $resultsHtml .= '<th>' . htmlspecialchars($col) . '</th>';
                    }
                    $resultsHtml .= '</tr>';

                    foreach ($rows as $r) {
                        $resultsHtml .= '<tr>';
                        foreach ($r as $val) {
                            $resultsHtml .= '<td>' . htmlspecialchars($val ?? 'NULL') . '</td>';
                        }
                        $resultsHtml .= '</tr>';
                    }

                    $resultsHtml .= '</table></div>';
                } else {
                    $messages[] = ['type' => 'info', 'text' => 'Query executed successfully but returned 0 rows.'];
                }
            }

            $conn->close();
        }
    } catch (Exception $e) {
        $messages[] = ['type' => 'error', 'text' => 'Error: ' . htmlspecialchars($e->getMessage())];
    }
}

$tables = [];
if (!$querySubmitted) {
    try {
        $conn = connect();
        $tres = $conn->query('SHOW TABLES');
        if ($tres) {
            while ($tr = $tres->fetch_array(MYSQLI_NUM)) {
                $tname = $tr[0];
                $count = 'N/A';
                $cres = $conn->query('SELECT COUNT(*) AS cnt FROM `' . $conn->real_escape_string($tname) . '`');
                if ($cres) {
                    $crow = $cres->fetch_assoc();
                    $count = $crow['cnt'] ?? 'N/A';
                    $cres->free();
                }
                $tables[] = ['name' => $tname, 'count' => $count];
            }
            $tres->free();
        }
        $conn->close();
    } catch (Exception $e) {
        // Unable to connect, tables list will be empty
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Brady Hauglie</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Inter, Arial, sans-serif;
            max-width: 1000px;
            margin: 20px auto;
            padding: 0 16px;
            background: #f6f7fb;
            color: #222;
        }
        header {
            margin-bottom: 20px;
        }
        h1 {
            font-size: 18px;
            margin: 0;
            color: #062e4a;
        }
        h2 {
            font-size: 16px;
            margin: 0 0 12px 0;
            color: #062e4a;
        }
        .card {
            background: #fff;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            margin-bottom: 16px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #333;
        }
        textarea {
            width: 100%;
            min-height: 120px;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-family: Courier, monospace;
            font-size: 13px;
            box-sizing: border-box;
        }
        .actions {
            margin-top: 10px;
            display: flex;
            gap: 8px;
        }
        button {
            padding: 8px 14px;
            border-radius: 6px;
            border: 0;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        button.primary {
            background: #0b63a6;
            color: #fff;
        }
        button.primary:hover {
            background: #094580;
        }
        button.ghost {
            background: #eef2f6;
            color: #333;
        }
        button.ghost:hover {
            background: #e2e8f0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #eef2f6;
            text-align: left;
        }
        th {
            background: #0b63a6;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .message {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .message.success {
            background: #e6f3ea;
            color: #0b6b40;
        }
        .message.error {
            background: #fdecea;
            color: #7a1b1b;
        }
        .message.info {
            background: #e9f5ff;
            color: #08324b;
        }
    </style>
</head>
<body>

<header>
    <h1>Database Query Tool - Brady Hauglie</h1>
    <?php if ($querySubmitted): ?>
        <form method="post" style="margin-top: 10px;">
            <button class="ghost" type="submit" name="reset" style="padding: 8px 14px; border-radius: 6px; border: 0; cursor: pointer; font-size: 14px; font-weight: 500; background: #eef2f6; color: #333;">Back to Tables</button>
        </form>
    <?php endif; ?>
</header>

<section class="card">
    <form method="post">
        <label for="query">SQL Query</label>
        <textarea id="query" name="query"><?php echo htmlspecialchars($_POST['query'] ?? ''); ?></textarea>
        <div class="actions">
            <button class="primary" type="submit" name="submit">Run Query</button>
            <button class="ghost" type="button" onclick="document.getElementById('query').value=''">Clear</button>
        </div>
    </form>
</section>

<?php foreach ($messages as $m): ?>
    <div class="message <?php echo htmlspecialchars($m['type']); ?>"><?php echo htmlspecialchars($m['text']); ?></div>
<?php endforeach; ?>

<?php echo $resultsHtml; ?>

<?php if (!$querySubmitted): ?>
<section class="card">
    <h2>Database Tables</h2>
    <?php if (count($tables) === 0): ?>
        <div class="message info">No tables found or unable to connect to database.</div>
    <?php else: ?>
        <?php foreach ($tables as $t): ?>
            <div style="margin-bottom: 20px;">
                <h3 style="margin: 10px 0 5px 0; font-size: 14px; color: #333;"><?php echo htmlspecialchars($t['name']); ?> (<?php echo htmlspecialchars($t['count']); ?> rows)</h3>
                <?php
                try {
                    $conn = connect();
                    $tname = $t['name'];
                    $res = $conn->query('SELECT * FROM `' . $conn->real_escape_string($tname) . '` LIMIT 100');
                    if ($res && $res->num_rows > 0) {
                        echo '<table style="font-size: 12px;"><tr>';
                        $fields = $res->fetch_fields();
                        foreach ($fields as $field) {
                            echo '<th>' . htmlspecialchars($field->name) . '</th>';
                        }
                        echo '</tr>';
                        while ($row = $res->fetch_assoc()) {
                            echo '<tr>';
                            foreach ($row as $val) {
                                echo '<td>' . htmlspecialchars($val ?? 'NULL') . '</td>';
                            }
                            echo '</tr>';
                        }
                        echo '</table>';
                        $res->free();
                    }
                    $conn->close();
                } catch (Exception $e) {
                    echo '<div class="message error">Unable to fetch table contents: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php endif; ?>
</body>
</html>
