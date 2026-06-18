<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
</head>
<body style="margin:0;padding:0;background:#f4f5f7;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background:linear-gradient(135deg,#6366f1,#8b5cf6);padding:28px 40px;">
                            <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:700;">New Contact Message</h1>
                            <p style="margin:6px 0 0;color:rgba(255,255,255,0.85);font-size:13px;">Someone submitted your portfolio contact form.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px 40px;">
                            <table width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;color:#374151;">
                                <tr>
                                    <td style="padding:6px 0;width:90px;color:#6b7280;font-weight:600;vertical-align:top;">From</td>
                                    <td style="padding:6px 0;">{{ $contactMessage->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;width:90px;color:#6b7280;font-weight:600;vertical-align:top;">Email</td>
                                    <td style="padding:6px 0;"><a href="mailto:{{ $contactMessage->email }}" style="color:#6366f1;text-decoration:none;">{{ $contactMessage->email }}</a></td>
                                </tr>
                                @if ($contactMessage->phone)
                                <tr>
                                    <td style="padding:6px 0;width:90px;color:#6b7280;font-weight:600;vertical-align:top;">Phone</td>
                                    <td style="padding:6px 0;">{{ $contactMessage->phone }}</td>
                                </tr>
                                @endif
                                @if ($contactMessage->subject)
                                <tr>
                                    <td style="padding:6px 0;width:90px;color:#6b7280;font-weight:600;vertical-align:top;">Subject</td>
                                    <td style="padding:6px 0;">{{ $contactMessage->subject }}</td>
                                </tr>
                                @endif
                            </table>

                            <div style="margin:24px 0 0;padding:20px;background:#f9fafb;border-radius:8px;border-left:4px solid #6366f1;">
                                <p style="margin:0 0 8px;color:#6b7280;font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Message</p>
                                <p style="margin:0;color:#374151;font-size:14px;line-height:1.7;white-space:pre-wrap;">{{ $contactMessage->message }}</p>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 40px 32px;">
                            <a href="{{ config('app.url') }}/admin/messages/{{ $contactMessage->id }}"
                               style="display:inline-block;padding:12px 28px;background:#6366f1;color:#ffffff;text-decoration:none;border-radius:8px;font-size:14px;font-weight:600;">
                                View in Admin Panel
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 40px;border-top:1px solid #f0f0f0;">
                            <p style="margin:0;color:#9ca3af;font-size:12px;text-align:center;">
                                This message was sent from your portfolio website contact form.<br>
                                Sent at {{ $contactMessage->created_at?->format('M d, Y \a\t g:i A') }}
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
