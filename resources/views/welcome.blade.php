<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Chat App</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>
<body>
    <div id="app"></div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>
    const pusher = new Pusher('a2ad9cbf82086edbc66d', {
      cluster: 'ap2',
    });

    const channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
      console.log(data, "my-ecentvhgv");
    });
    </script>
</body>

</html>
