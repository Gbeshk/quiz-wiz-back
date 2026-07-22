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
            style="background-color: #4b69fd; border-radius: 8px"
        >
            <a
                href="{{ $url }}"
                target="_blank"
                style="
                    display: inline-block;
                    padding: 10px 32px;
                    font-family: &quot;Inter&quot;, Arial, sans-serif;
                    font-size: 16px;
                    font-weight: 600;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 8px;
                "
            >
                {{ $slot }}
            </a>
        </td>
    </tr>
</table>
