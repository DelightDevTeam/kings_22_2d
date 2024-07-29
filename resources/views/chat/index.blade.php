<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat Laravel Pusher | Edlin App</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- JavaScript -->
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <!-- End JavaScript -->

  <!-- CSS -->
  <link rel="stylesheet" href="/style.css">
  <!-- End CSS -->

</head>
<body>
<div class="chat">

  <!-- Chat Messages -->
  <div class="messages">
    @foreach($messages as $message)
      <div class="message">
        <p><strong>{{ $message->sender->name }}:</strong> {{ $message->message }}</p>
      </div>
    @endforeach
  </div>
  <!-- End Chat Messages -->

  <!-- Message Input -->
  <div class="bottom">
    <form id="chat-form">
      <input type="text" id="message" name="message" placeholder="Enter message..." autocomplete="off">
      <button type="submit">Send</button>
    </form>
  </div>
  <!-- End Message Input -->

</div>
</body>

<script>
  // Pusher Initialization
  const pusher = new Pusher('{{ config('broadcasting.connections.pusher.key') }}', {
    cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
    encrypted: true
  });

  // Subscribe to the channel
  const channel = pusher.subscribe('private-chat.1'); // Example chat id

  // Bind to the event
  channel.bind('MessageSent', function(data) {
    console.log('Received data:', data);

    // Append the new message to the chat
    $(".messages").append(
      `<div class="message">
         <p><strong>${data.sender}:</strong> ${data.message}</p>
       </div>`
    );

    // Scroll to the bottom
    $(document).scrollTop($(document).height());
  });

  // Send message
  $("#chat-form").submit(function(event) {
    event.preventDefault();

    $.ajax({
      url: '/live-chat/messages',
      method: 'POST',
      data: {
        chat_id: 1, // Example chat id
        sender_id: 1, // Example sender id
        message: $("#message").val(),
        _token: '{{ csrf_token() }}'
      }
    }).done(function(res) {
      console.log('Message sent:', res);
      $("#message").val('');
    }).fail(function(xhr, status, error) {
      console.log('Error:', error);
    });
  });
</script>
</html>
