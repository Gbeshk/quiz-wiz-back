<!DOCTYPE html>
<html
    lang="en"
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office"
>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Verify your email</title>
    <style>
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
    </style>
</head>
<body
    style="
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        background-color: #ffffff;
    "
>
    <div
        style="
            display: none;
            max-height: 0px;
            overflow: hidden;
            mso-hide: all;
            font-size: 1px;
            line-height: 1px;
            max-width: 0px;
            opacity: 0;
        "
    >
        You're almost there! Verify your email address to get started.
    </div>

    <table
        role="presentation"
        width="100%"
        border="0"
        cellpadding="0"
        cellspacing="0"
        style="background-color: #ffffff; width: 100%"
    >
        <tr>
            <td align="center" style="padding: 48px 16px">
                <table
                    role="presentation"
                    width="100%"
                    border="0"
                    cellpadding="0"
                    cellspacing="0"
                    style="max-width: 455px; width: 100%"
                >
                    <tr>
                        <td align="center" style="padding-bottom: 10px">
                            <table
                                role="presentation"
                                border="0"
                                cellpadding="0"
                                cellspacing="0"
                                align="center"
                            >
                                <tr>
                                    <td align="center">
                                        <img
                                            src="{{ rtrim(config('app.frontend_url'), '/') }}/images/icons/logo.png"
                                            width="35"
                                            height="20"
                                            alt="{{ config('app.name') }}"
                                            style="
                                                display: block;
                                                border: 0;
                                                outline: none;
                                                margin: 0 auto;
                                            "
                                        />
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        align="center"
                                        style="
                                            padding-top: 10px;
                                            line-height: 0;
                                            font-size: 0px;
                                        "
                                    >
                                        <img
                                            src="{{ rtrim(config('app.frontend_url'), '/') }}/images/icons/logo-text.png"
                                            width="73"
                                            height="14"
                                            alt="{{ config('app.name') }}"
                                            style="
                                                display: block;
                                                border: 0;
                                                outline: none;
                                                margin: 0 auto;
                                            "
                                        />
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 24px 24px 0 24px">
                            <h1
                                style="
                                    margin: 0;
                                    font-family:
                                        &quot;Raleway&quot;, Arial, sans-serif;
                                    font-size: 32px;
                                    line-height: 40px;
                                    color: #000000;
                                    font-weight: 700;
                                    text-align: center;
                                "
                            >
                                Verify your email address to get started
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
                                            font-family:
                                                &quot;Inter&quot;, Arial,
                                                sans-serif;
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

                                        <p style="margin: 0 0 24px 0; font-size: 16px; line-height: 20px; color: #000000; font-weight: 400">You're almost there! To complete your sign up, please verify your email address.</p>

                                        <table
                                            role="presentation"
                                            border="0"
                                            cellpadding="0"
                                            cellspacing="0"
                                            style="margin-bottom: 8px"
                                        >
                                            <tr>
                                                <td
                                                    align="center"
                                                    bgcolor="#4B69FD"
                                                    style="
                                                        background-color: #4b69fd;
                                                        border-radius: 8px;
                                                    "
                                                >
                                                    <a
                                                        href="{{ $url }}"
                                                        target="_blank"
                                                        style="
                                                            display: inline-block;
                                                            padding: 10px 32px;
                                                            font-family:
                                                                &quot;Inter&quot;,
                                                                Arial,
                                                                sans-serif;
                                                            font-size: 16px;
                                                            font-weight: 600;
                                                            color: #ffffff;
                                                            text-decoration: none;
                                                            border-radius: 8px;
                                                        "
                                                    >
                                                        Verify now
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
