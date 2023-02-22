<div class="container-fluid">
  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Users box-->
    <div class="col-md-4 col-lg-5 col-12 px-0">
      <div class="bg-white">

        <div class="bg-gray px-4 py-2 bg-light">
          <h4 class="mb-0 py-2">Nedávné chaty</h4>
        </div>

        <div class="messages-box">
          <div class="list-group rounded-0">
            <?php foreach ($chats as $chat) : ?>
              <a href="/zpravy?chat=<?= $chat['chat_id'] ?>" class="list-group-item list-group-item-action rounded-0 <?= (isset($_GET['chat']) && !empty($_GET['chat']) && $chat['chat_id'] == $_GET['chat']) ? "active text-white" : "list-group-item-light" ?>">
                <div class="media">
                  <div class="media-body ml-4" id="preview_<?= $chat['chat_id'] ?>">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                      <h6 class="mb-0">
                        <?= $chat['first_name'] ?> <?= $chat['last_name'] ?> (<?= $chat['email'] ?>)
                      </h6>
                      <small class="small font-weight-bold" id="preview_date_sent_<?= $chat['chat_id'] ?>">
                        <i><?= !empty($chat['date_sent']) ? date('d.m.Y H:i:s', strtotime($chat['date_sent'])) : "" ?></i>
                      </small>
                    </div>
                    <p class="font-italic mb-0 ch-text-small" id="preview_last_text_<?= $chat['chat_id'] ?>">
                      <?= strlen($chat['last_text']) >= 60 ? substr($chat['last_text'], 0, 60) . "..." : $chat['last_text'] ?>
                    </p>
                  </div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <!-- Chat Box-->
    <div class="col-md-8 col-lg-7 col-12 px-0">
      <div class="px-4 py-5 chat-box bg-white" id="messages-container">
        <?php if (isset($_GET['chat']) && !empty($_GET['chat']) && isset($messages) && !empty($messages)) : ?>
          <?php foreach ($messages as $message) : ?>
            <?php
            $mine = $message['sender'] == $_SESSION['user_data']['user_id'];
            $system_message = $message['sender'] == 0;
            ?>
            <?php if ($system_message) : ?>
              <div class="media w-100 mb-3">
                <div class="media-body">
                  <div class="rounded py-4 px-3 mb-2 bg-dark">
                    <p class="mb-0 text-white"><?= $message['text'] ?></p>
                  </div>
                </div>
              </div>
            <?php else : ?>
              <div class="media w-50 mb-3 <?= ($mine) ? "ms-auto" : "" ?>">
                <div class="media-body <?= (!$mine) ? "ml-3" : "" ?>">
                  <div class="<?= ($mine) ? "chat-background" : "bg-light" ?> rounded py-2 px-3 mb-2">
                    <p class="ch-text-small mb-0 <?= ($mine) ? "text-white" : "text-muted" ?>"><?= $message['text'] ?></p>
                  </div>
                  <p class="small text-muted"><?= date('d.m.Y H:i:s', strtotime($message['date_sent'])) ?></p>
                </div>
              </div>
            <?php endif; ?>


          <?php endforeach; ?>
        <?php else : ?>
          Zatím zde nejsou žádné zprávy
        <?php endif; ?>
      </div>

      <!-- Typing area -->
      <div class="border bg-light">
        <form action="" id="form-send-message">
          <div class="input-group">
            <input type="text" placeholder="Napište zprávu..." aria-describedby="btn-send-message" class="chat-input form-control rounded-0 border-0 py-4 bg-light shadow-none" id="message-input" required>
            <div class="input-group-append">
              <button id="btn-send-message" type="submit" class="btn btn-link h-100"> <i class="fa fa-paper-plane"></i></button>
            </div>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
<script>
  // Scroll down on chat open
  <?php if (isset($_GET['chat']) && !empty($_GET['chat']) && isset($messages) && !empty($messages)) : ?>
    $(document).ready(function() {
      // Stolen from:
      // https://stackoverflow.com/questions/10503606/scroll-to-bottom-of-div-on-page-load-jquery
      $(".chat-box").animate({
        scrollTop: $('.chat-box').prop("scrollHeight")
      }, 750);
    });
  <?php endif; ?>
  // WebSocket connection
  let socket = io('<?= WEBSOCKETS_PROTOCOL ?>://<?= SITE_URL ?>:<?= WEBSOCKETS_PORT ?>', {
    secure: true
  });

  socket.on('message_sent', data => {
    data = JSON.parse(data);
    const preview_id = "preview_" + data.chat_id;

    let sender_id = parseInt(data.sender);
    let mine = <?= intval($_SESSION['user_data']['user_id']) ?> == sender_id;
    let date_sent = new Date(Date.parse(data.date_sent));

    let formated_date = ("0" + date_sent.getDate()).slice(-2) + "." + ("0" + (parseInt(date_sent.getMonth()) + 1)).slice(-2) + "." + date_sent.getFullYear() + " " + ("0" + date_sent.getHours()).slice(-2) + ":" + ("0" + date_sent.getMinutes()).slice(-2) + ":" + ("0" + date_sent.getSeconds()).slice(-2);
    let element = ``;
    
    // If is System Message
    if(sender_id == 0)
    {
      element = `<div class="media w-100 mb-3">
                    <div class="media-body">
                      <div class="rounded py-4 px-3 mb-2 bg-dark">
                        <p class="mb-0 text-white">` + data.text + `</p>
                      </div>
                    </div>
                  </div>`;
    }
    else // It's user message
    {
      element = `<div class="media w-50 mb-3 ` + ((mine) ? `ms-auto` : ``) + `">
                    <div class="media-body ` + ((!mine) ? `ml-3` : ``) + `">
                      <div class="` + ((mine) ? `chat-background` : `bg-light`) + ` rounded py-2 px-3 mb-2">
                        <p class="ch-text-small mb-0 ` + ((mine) ? `text-white` : `text-muted`) + `">` + data.text + `</p>
                      </div>
                      <p class="small text-muted">` + formated_date + `</p>
                    </div>
                  </div>`;
    }

    $("#messages-container").ready(function() {
      $("#" + preview_id + " #preview_date_sent_" + data.chat_id).text(data.date_sent);
      $("#" + preview_id + " #preview_last_text_" + data.chat_id).text(data.text);
    });

    // https://stackoverflow.com/questions/270612/scroll-to-bottom-of-div
    $('#messages-container').stop().animate({
      scrollTop: $('#messages-container')[0].scrollHeight
    }, 400);

    $("#messages-container").append(element);
  });

  $("#form-send-message").submit(function(e) {

    e.preventDefault();

    const url_params = new URLSearchParams(window.location.search);
    let chat_id = url_params.get('chat');
    let message = $("#message-input").val();

    $.ajax({
      type: "POST",
      dataType: "json",
      url: "/send-message",
      data: {
        "chat_id": chat_id,
        "message": message,
      },
    });

    $("#message-input").val("");
  });
</script>