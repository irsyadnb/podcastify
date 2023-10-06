<div id="template">
  <?php

  $user = $data['user'];

  echo '
      <div class="profile-body">
        <img class="profile-image" src="' . ($user->avatar_url ?? (IMAGES_DIR . 'avatar-template.png')) . '" alt="Profile Image">
        <div class="profile-info">
          <p>Profile</p>
          <h1 id="profile-username">' . $user->username . '</h1>
          <div class="profile-ext-info">
            <p id="profile-fullname">' . $user->first_name . " " . $user->last_name . '</p>
            <span style="padding: 0 4px 0 8px;">∙</span>
            <p id="profile-email">' . $user->email . '</p>
          </div>
        </div>
      </div>

      <div class="edit-wrapper">
        <button class="btn btn-edit" onclick="showModalEditUser(' . $user->user_id . ', \'userModalEdit\')">
          <img src="' . ICONS_DIR . 'triple-dots.svg" alt="Edit Button">
        </button>
      </div>
    ';
  ?>

  <?php
  require_once COMPONENTS_SHARES_DIR . 'modals/updateModal.php';

  echoUpdateModalTop("userModalEdit", "Profile details");
  ?>

  <form method="" class="modal-body" id="userModalEdit-form">
    <div class="modal-image">
      <img class="avatar" src="" alt="Profile Image">
    </div>
    <input type="hidden" name="user_id" id="userModalEdit-user_id" value="">
    <div class="modal-form">
      <?php
      require_once VIEWS_DIR . "/components/shares/inputs/text.php";
      echoInputText("email", 1, false, false, true);

      echoInputText("username", 2, false, false, true);

      echoInputText("first_name", 3, false, false, true);

      echoInputText("last_name", 4, false, false, true);
      ?>
      <div class="btn-wrapper">
        <div class="change-pass-wrapper">
          <button type="button" class="btn-change-pass" onclick="showModalChangePass('userModalEdit', 'changePasswordModal', <?= $user->user_id ?>)">
            Change Password
          </button>
        </div>
        <button type="submit" class="btn secondary btn-save">
          Save
        </button>
      </div>
    </div>
  </form>

  <?php
  $description = "By proceeding, you agree to change your personal information. Please make sure you have the rights.";
  echoUpdateModalBottom($description);
  ?>

  <?php
  // Change Password
  require_once COMPONENTS_SHARES_DIR . 'modals/updateModal.php';

  echoUpdateModalTop("changePasswordModal", "Change password");
  ?>

  <form method="" class="modal-body" id="changePasswordModal-form">
    <input type="hidden" name="user_id" id="changePasswordModal-user_id" value="">
    <div class="modal-form">
      <?php
      require_once VIEWS_DIR . "/components/shares/inputs/text.php";
      echoInputText("current_password", 1, true, false, true);

      echoInputText("password", 2, true, false, true);

      echoInputText("confirm_password", 3, true, false, true);

      echoJsFile();
      ?>
      <div class="btn-wrapper-submit">
        <button type="submit" class="btn secondary btn-save">Submit</button>
      </div>
    </div>
  </form>

  <?php
  $description = "By proceeding, you agree to change Podcastify users's status. Please make sure you have the rights.";
  echoUpdateModalBottom($description);

  ?>
</div>

<!-- Override Styles -->

<head>
  <link rel=" stylesheet" href="<?= CSS_DIR ?>user/profile.css">
</head>

<script src="<?= JS_DIR ?>/user/profile.js"></script>
<script src="<?= JS_DIR ?>/components/modal.js"></script>