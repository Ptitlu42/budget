<!DOCTYPE html>
<html>
<head>
    <title>Invitation to join a group</title>
</head>
<body>
    <h1>Invitation to join a group</h1>

    <p>Hello,</p>

    <p>{{ $inviter }} invites you to join the group "{{ $group }}" on Budget application.</p>

    <p>To accept this invitation, please click on the following link:</p>

    <p><a href="{{ url(route('groups.join', $token)) }}">Join group</a></p>

    <p>If you don't want to join this group, you can ignore this email.</p>

    <p>Best regards,<br>Budget Team</p>
</body>
</html>
