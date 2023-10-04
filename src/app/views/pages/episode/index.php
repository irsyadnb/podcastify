<head>
    <link rel="stylesheet" href="<?= CSS_DIR ?>episode/episode_card.css">
</head>
<div id="template">
  <div class="episode-list-container">
    <?php 
    require_once VIEWS_DIR . "components/episode/episode_card.php";
    
    foreach ($data['episodes'] as $episode) {
      episode_card($episode, "showEpisode($episode->episode_id)");
    }
    ?>
  </div>
</div>
<script src="<?= JS_DIR ?>/episode/episode.js"></script>