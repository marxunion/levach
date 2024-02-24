<?php require "backend/views/components/header_ru.php" ?>
<div class="wrapper">
  <div class="content">
    <h1 class="title">Error</h1>
    <p><b>ErrorCode:</b> <?= $errno ?></p>
    <p><b>ErrorMessage:</b> <?= $errstr ?></p>
    <p><b>ErrorFileName:</b> <?= $errfile ?></p>
    <p><b>ErrorLine:</b> <?= $errline ?></p>
  </div>
</div>