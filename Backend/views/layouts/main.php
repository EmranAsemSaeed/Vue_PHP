<?php
use App\Core\App;
$title = $title ?? App::config('app.name', 'App');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars($title) ?> - <?= htmlspecialchars(App::config('app.name', 'App')) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{--bg:#0f172a;--fg:#e2e8f0;--muted:#94a3b8;--card:#111827;--accent:#3b82f6}
    *{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--fg);font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,"Helvetica Neue",Arial}
    a{color:var(--accent);text-decoration:none}
    header,main,footer{max-width:960px;margin:auto;padding:16px}
    nav{display:flex;gap:12px;align-items:center;justify-content:space-between}
    .card{background:var(--card);border:1px solid #1f2937;border-radius:14px;padding:16px;margin-top:16px}
    table{width:100%;border-collapse:collapse}
    th,td{padding:10px;border-bottom:1px solid #1f2937}
    .btn{display:inline-block;padding:8px 12px;border-radius:10px;border:1px solid #1f2937;background:#111827;color:#e5e7eb}
    .btn:hover{filter:brightness(1.1)}
    .muted{color:var(--muted)}
    .right{float:right}
  </style>
</head>
<body>
<header>
  <nav>
    <div><strong><?= htmlspecialchars(App::config('app.name', 'App')) ?></strong></div>
    <div>
      <?php if (!empty($_SESSION['user'])): ?>
        <span class="muted">Hi, <?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        &nbsp;•&nbsp;<a class="btn" href="<?= App::baseUrl('users') ?>">Users</a>
        &nbsp;•&nbsp;<a class="btn" href="<?= App::baseUrl('logout') ?>">Logout</a>
      <?php else: ?>
        <a class="btn" href="<?= App::baseUrl('login') ?>">Login</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
<main>
  <div class="card">
    <?= $content ?>
  </div>
</main>
<footer>
  <p class="muted">&copy; <?= date('Y') ?> <?= htmlspecialchars(App::config('app.name', 'App')) ?></p>
</footer>
</body>
</html>
