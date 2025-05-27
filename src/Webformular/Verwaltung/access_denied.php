<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Security-Policy" content="default-src: https: data: 'unsafe-inline' 'unsafe-eval';" />
    <title>Zugriff verweigert - Access denied</title>
    <link rel="shortcut icon" href="https://www.uni-muenster.de/imperia/md/content/allgemein/farbunabhaengig/favicon.ico" />
    <style type="text/css">
        @font-face {
            font-family: 'MetaWebPro';
            font-weight: 400;
            font-style: normal;
            src: url(https://www.uni-muenster.de/imperia/md/content/allgemein/farbunabhaengig/fonts/metawebpro-normal.woff) format('woff');
        }

        @font-face {
            font-family: 'MetaWebPro';
            font-weight: 700;
            font-style: normal;
            src: url(https://www.uni-muenster.de/imperia/md/content/allgemein/farbunabhaengig/fonts/metawebpro-bold.woff) format('woff');
        }

        html {
            font-size: 1.1em;
            line-height: 1.4;
            font-family: MetaWebPro, Calibri, Carlito, Verdana, sans-serif;
        }

        body {
            margin: 0;
        }

        header {
            margin: 1rem 1rem 2rem 1rem;
            border-bottom: 4px solid rgb(66, 60, 57);
            padding-bottom: 1rem;
        }

        header img {
            width: 280px;
            max-width: 100%;
        }

        article {
            margin: 1rem;
            border: 2px solid rgb(190, 198, 200);
        }

        article>div {
            border-top: 25px solid rgb(122, 181, 29);
            padding-top: 1rem;
        }

        h1 {
            margin: 2rem 1rem 1rem 1rem;
            font-size: 2rem;
            line-height: 1.3;
        }

        h2 {
            margin: 2rem 1rem 1rem 1rem;
            font-size: 1.6rem;
            line-height: 1.3;
        }

        p {
            margin: 1rem;
        }

        article a {
            color: #006e89;
            text-decoration: none;
        }

        article a:before {
            content: "\2192\A0";
        }

        p.host {
            text-align: right;
            color: rgb(190, 198, 200);
        }

        footer {
            margin: 1rem;
            padding: 1rem;
            background-color: rgb(62, 62, 60);
            color: rgb(255, 255, 255);
            text-align: right;
        }

        footer>a {
            display: block;
            font-size: 36px;
            font-weight: bold;
            line-height: 1;
            text-align: right;
            text-decoration: none;
            color: rgb(255, 255, 255);
        }
    </style>
</head>

<body>
    <header><a href="https://www.uni-muenster.de/"><img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmVyc2lvbj0iMS4xIiB4PSIwcHgiIHk9IjBweCIgdmlld0JveD0iMCAwIDIxMi42IDYxLjUiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSIyMTIuNjAwMDEiIGhlaWdodD0iNjEuNSIgc3R5bGU9ImZpbGw6IzNlM2UzYiI+CjxwYXRoIGQ9Im0yMDcuOCw1My4zIDEuMSwwYzAuNSwwIDAuOSwwLjEgMS4yLDAuMiAwLjUsMC4yIDAuOSwwLjggMC45LDEuMyAwLDAuNi0wLjIsMS0wLjUsMS4zLTAuMywwLjMtMC44LDAuNC0xLjcsMC40bC0xLDB6bTEuMSwtMC44LTIuMSwwIDAsOC43IDEuMSwwIDAsLTMuOWMwLjUsMCAwLjcsMC4xIDEsMC42IDEuMiwxLjUgMi4xLDIuOCAyLjMsMy40bDEuNCwwYzAsMC0xLjcsLTIuNS0yLjEsLTMtMC4yLC0wLjItMC41LC0wLjYtMC45LC0xbDAuMSwwYzEuNSwwIDIuNCwtMSAyLjQsLTIuNCAwLC0wLjktMC41LC0xLjUtMC45LC0xLjgtMC41LC0wLjQtMS4xLC0wLjYtMi4zLC0wLjZtLTYuMSwwLTUsMCAwLDguNyA1LjIsMCAwLC0wLjktNCwwIDAsLTMuMiAzLjIsMCAwLC0wLjktMy4zLDAgMCwtMi45IDMuOCwwem0tOC41LDAtNi4xLDAgMCwwLjkgMi41LDAgMCw3LjkgMS4xLDAgMCwtNy45IDIuNSwwem0tMTUuMiw3LjMtMC41LDAuOGMwLjksMC41IDEuOCwwLjggMi45LDAuOCAwLjgsMCAxLjUsLTAuMiAyLjEsLTAuNSAwLjgsLTAuNSAxLjMsLTEuMyAxLjMsLTIuMSAwLC0wLjUtMC4yLC0xLjEtMC42LC0xLjUtMC40LC0wLjQtMC44LC0wLjYtMS42LC0wLjlsLTEuMSwtMC4zYy0xLjEsLTAuMy0xLjUsLTAuNy0xLjUsLTEuNCAwLC0wLjkgMC43LC0xLjQgMS44LC0xLjQgMC44LDAgMS40LDAuMiAyLjIsMC43bDAuNSwtMC44Yy0wLjksLTAuNi0xLjgsLTAuOC0yLjgsLTAuOC0xLjgsMC0zLDEtMywyLjUgMCwwLjYgMC4yLDEgMC42LDEuNCAwLjQsMC40IDAuOCwwLjUgMS42LDAuOGwwLjksMC4zYzEuMSwwLjMgMS42LDAuOSAxLjYsMS42IDAsMC41LTAuMiwwLjktMC43LDEuMi0wLjQsMC4zLTAuOCwwLjQtMS41LDAuNC0wLjcsLTAuMS0xLjQsLTAuMy0yLjIsLTAuOG0tMjAsLTcuMy0xLjEsMCAwLDYuM2MwLDAuNCAwLDAuOSAwLjMsMS40IDAuNSwwLjkgMS4zLDEuMyAyLjcsMS4zIDEuMSwwIDEuOSwtMC4yIDIuNCwtMC43IDAuNSwtMC40IDAuNywtMC45IDAuNywtMmwwLC02LjItMS4xLDAgMCw2LjFjMCwwLjYgMCwxLTAuMywxLjQtMC4zLDAuNC0wLjksMC41LTEuNiwwLjUtMS4xLDAtMS42LC0wLjUtMS44LC0wLjktMC4xLC0wLjMtMC4yLC0wLjgtMC4yLC0xLjN6bTQsLTEuMmMwLC0wLjQtMC4zLC0wLjYtMC43LC0wLjYtMC40LDAtMC43LDAuMy0wLjcsMC42IDAsMC40IDAuMywwLjcgMC43LDAuNyAwLjQsMCAwLjcsLTAuNCAwLjcsLTAuN20tMy41LDAuNmMwLjQsMCAwLjcsLTAuMyAwLjcsLTAuNyAwLC0wLjQtMC4zLC0wLjYtMC43LC0wLjYtMC40LDAtMC43LDAuMy0wLjcsMC42IDAsMC40IDAuNCwwLjcgMC43LDAuN20tMTMuNywwLjYtMC44LDguNyAxLjEsMCAwLjUsLTYuMmMwLC0wLjUgMC4xLC0xLjUgMC4xLC0xLjcgMCwwLjIgMC4yLDAuOCAwLjUsMS43bDEuOCw2LjIgMC45LDAgMiwtNi41YzAuMiwtMC41IDAuMywtMS4zIDAuNCwtMS40IDAsMC4xIDAsMC45IDAuMSwxLjVsMC41LDYuNCAxLjEsMC0wLjgsLTguNy0xLjYsMC0xLjcsNS44Yy0wLjIsMC43LTAuMywxLjMtMC4zLDEuNCAwLC0wLjEtMC4xLC0wLjctMC40LC0xLjVsLTEuNywtNS43em0yMy45LDAtMS4zLDAgMCw4LjcgMS4xLDAtMC4xLC01LjFjMCwtMS4xLTAuMSwtMi40LTAuMSwtMi40IDAsMCAwLjUsMSAxLDIuMWwzLDUuMyAxLjIsMCAwLC04LjctMS4xLDAgMCw0LjhjMCwxLjIgMC4xLDIuNiAwLjEsMi43IDAsLTAuMS0wLjQsLTEtMC45LC0xLjh6bTQyLjgsLTEzLjQgMCwtMTQuMy00LjMsMCAwLDEzLjZjMCwxLjEgMCwxLjQtMC4xLDEuOS0wLjIsMS42LTEuNCwyLjUtMy4yLDIuNS0xLjQsMC0yLjQsLTAuNS0yLjksLTEuNS0wLjIsLTAuNS0wLjQsLTEuMi0wLjQsLTIuNWwwLC0xNC00LjQsMCAwLDE0LjhjMCwyIDAuMiwyLjkgMC45LDQgMS4yLDEuOSAzLjYsMi44IDYuOCwyLjggNC42LDAgNi43LC0yLjMgNy4yLC00IDAuNCwtMC45IDAuNCwtMS4zIDAuNCwtMy4zIiAvPjxwYXRoIGQ9Im0xNjIuMyw0Ni4yIDUuMSwtMjEuNS00LjUsMC0xLjcsOC4xYy0wLjUsMi4xLTEuMSw2LjQtMS4yLDcuMSAwLDAtMC41LC0zLjYtMC45LC01LjZsLTIsLTkuNS00LjcsMC0xLjksOC42Yy0wLjYsMi45LTEsNi0xLjEsNi44IDAsMC0wLjMsLTMtMS4xLC03bC0xLjgsLTguNC00LjUsMCA1LjEsMjEuNSA0LjksMCAxLjgsLTguN2MwLjYsLTIuOCAwLjksLTUuNSAxLC02IDAuMSwwLjggMC40LDMuNCAxLDYuMWwxLjksOC43IDQuNiwweiIgaWQ9InciIC8+PHVzZSB4bGluazpocmVmPSIjdyIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMjcuMiwwKSIgLz48cmVjdCB4PSI2My43IiB5PSIwIiB3aWR0aD0iMiIgaGVpZ2h0PSI0LjYiIC8+PHJlY3QgeD0iNjAuNCIgeT0iNi40IiB3aWR0aD0iOC43IiBoZWlnaHQ9IjIuMiIgLz48cmVjdCB4PSI1Ny40IiB5PSIxNS44IiB3aWR0aD0iMTQuNyIgaGVpZ2h0PSI0LjYiIC8+PHJlY3QgeD0iMCIgeT0iMjQuOCIgd2lkdGg9IjUwLjMiIGhlaWdodD0iNC42IiBpZD0iYSIgLz48dXNlIHhsaW5rOmhyZWY9IiNhIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSg3OS4yLDApIiAvPjxyZWN0IHg9IjAiIHk9IjM3LjYiIHdpZHRoPSIxMjkuNiIgaGVpZ2h0PSIyLjIiIC8+PHJlY3QgeD0iMCIgeT0iNTYuNiIgd2lkdGg9IjEyOS42IiBoZWlnaHQ9IjQuNiIgLz48L3N2Zz4=" alt="WWU Münster" /></a></header>
    <article>
        <div>
            <div lang="de" xml:lang="de">
                <h1>Zugriff verweigert</h1>
                <p>Leider geh&ouml;ren Sie, angemeldet als <tt><?php echo $_SESSION['username']; ?></tt>, nicht zu dem Personenkreis, der berechtigt
                    ist, die angeforderte Information abzurufen.</p>
                <p>Haben Sie vielleicht vergessen, die Mitgliedschaft in einer entsprechenden Nutzergruppe zu beantragen
                    oder zu verl&auml;ngern?</p>
            </div>
            <div lang="en" xml:lang="en">
                <h1>Access denied</h1>
                <p>Unfortunately you, logged in as <tt><?php echo $_SESSION['username']; ?></tt>, are not belonging to the group of people that is
                    authorized to access the requested object.</p>
                <p>Did you by any chance forget to apply for or to prolong membership in a relevant user group?</p>
            </div>
        </div>
    </article>
    <footer><a href="https://www.uni-muenster.de/">wissen.leben</a></footer>
</body>

</html>