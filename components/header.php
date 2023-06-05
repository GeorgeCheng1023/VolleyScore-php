<nav class="navbar navbar-expand-lg bg-body-tertiary mb-3" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">排球計分系統</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <?php

      if (isset($_COOKIE['teamID'])) {
        echo '
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            編輯資料
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="team.php">管理隊伍</a></li>
            <li><a class="dropdown-item" href="player.php">
                管理球員
              </a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="race.php">比賽系統</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="analyze.php">球員分析</a>
        </li>
      </ul>';
        echo '<form action="logout.php">';
        echo '<button class="btn btn-primary">';
        echo '登出';
        echo '</button>';
        echo '</form>';
      } else {
        echo '<div class="me-auto"></div>
        <a href="login.php" class="btn btn-primary">';
        echo '登入';
        echo '</a>';
      }
      ?>

      </a>

    </div>
  </div>
</nav>