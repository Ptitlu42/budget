<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Group Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 600px; margin: 20px auto; padding: 20px;">
    <div style="text-align: center; margin-bottom: 30px;">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="11" stroke="#4299E1" stroke-width="2"/>
            <path d="M7 12.5L10.5 16L17 9" stroke="#4299E1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    <div style="text-align: center;">
        <h1 style="color: #2D3748; margin-bottom: 30px;">Join Budget</h1>
    </div>

    <div style="margin-bottom: 30px;">
        <p>Hello,</p>

        <p><strong>{{ $inviter }}</strong> has invited you to join the group <strong>"{{ $group }}"</strong> on Budget, the app that makes shared expense management simple.</p>

        <div style="background: #F7FAFC; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <div style="text-align: center; margin-bottom: 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 20H7C5.89543 20 5 19.1046 5 18V6C5 4.89543 5.89543 4 7 4H17C18.1046 4 19 4.89543 19 6V18C19 19.1046 18.1046 20 17 20Z" stroke="#4299E1" stroke-width="2"/>
                    <path d="M15 8H9" stroke="#4299E1" stroke-width="2" stroke-linecap="round"/>
                    <path d="M15 12H9" stroke="#4299E1" stroke-width="2" stroke-linecap="round"/>
                    <path d="M15 16H9" stroke="#4299E1" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <p style="margin: 0; text-align: center;">Track shared expenses</p>
        </div>

        <div style="background: #F7FAFC; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <div style="text-align: center; margin-bottom: 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#4299E1" stroke-width="2"/>
                    <path d="M15 9C15 7.34315 13.6569 6 12 6C10.3431 6 9 7.34315 9 9C9 10.6569 10.3431 12 12 12C13.6569 12 15 13.3431 15 15C15 16.6569 13.6569 18 12 18" stroke="#4299E1" stroke-width="2"/>
                    <path d="M12 5V6" stroke="#4299E1" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 18V19" stroke="#4299E1" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
            <p style="margin: 0; text-align: center;">Split bills easily</p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url(route('groups.join', $token)) }}" style="background: #4299E1; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">Join Group</a>
        </div>

        <p style="color: #718096; font-size: 14px; text-align: center;">If you didn't request to join this group, you can ignore this email.</p>
    </div>

    <div style="text-align: center; color: #718096; font-size: 14px; border-top: 1px solid #E2E8F0; padding-top: 20px;">
        <p>Best regards,<br>P'tit Lu Budget Team</p>
        <p style="font-size: 12px;">This is an automated email, please do not reply.</p>
    </div>
</body>
</html>
