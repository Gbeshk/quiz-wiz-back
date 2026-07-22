<x-mail.layout
    :title="'Reset your password'"
    :preheader="'Reset your password to get back into your account.'"
>
    <tr>
        <td align="center" style="padding: 24px 24px 0 24px">
            <h1
                style="
                    margin: 0;
                    font-family: &quot;Raleway&quot;, Arial, sans-serif;
                    font-size: 32px;
                    line-height: 40px;
                    color: #000000;
                    font-weight: 700;
                    text-align: center;
                "
            >
                Reset your password
            </h1>
        </td>
    </tr>

    <tr>
        <td style="padding: 24px 24px 0 24px">
            <table
                role="presentation"
                width="100%"
                border="0"
                cellpadding="0"
                cellspacing="0"
            >
                <tr>
                    <td
                        align="left"
                        style="
                            font-family: &quot;Inter&quot;, Arial, sans-serif;
                        "
                    >
                        <p
                            style="
                                margin: 0 0 24px 0;
                                font-size: 16px;
                                line-height: 20px;
                                color: #000000;
                                font-weight: 400;
                            "
                        >Hi {{ $user->username ?? 'there' }},</p>

                        <p style="margin: 0 0 24px 0; font-size: 16px; line-height: 20px; color: #000000; font-weight: 400">We received a request to reset your password. Click the button below to choose a new one.</p>

                        <x-mail.button :url="$url">
                            Reset password
                        </x-mail.button>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</x-mail.layout>
