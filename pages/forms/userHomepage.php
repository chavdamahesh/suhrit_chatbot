
<?php
session_set_cookie_params(60);
ini_set('session.gc_maxlifetime', 60);
session_start();

// If session expired or missing, redirect to login
if (!isset($_SESSION['user_id'])) {
    // Optionally clear cookies if you want to fully logout
    setcookie("user_id", "", time() - 3600, "/");
    setcookie("email", "", time() - 3600, "/");
    setcookie("role", "", time() - 3600, "/");

    header("Location: ../../../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Homepage</title>
    <style>
        /* Chat Bubble Button */
        #chatBubble {
            position: fixed;
            bottom: 24px;
            right: 24px;
            width: 60px;
            height: 60px;
            background-color: #4e8cff;
            border-radius: 50%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 30px;
            cursor: pointer;
            z-index: 999;
            transition: background-color 0.3s;
        }

        #chatBubble:hover {
            background-color: #3b6dd8;
        }

        /* Chat Window */
        #chatWindow {
            position: fixed;
            bottom: 100px;
            right: 24px;
            width: 360px;
            max-height: 500px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            display: none;
            flex-direction: column;
            z-index: 1000;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header */
        #chatHeader {
            background-color: #4e8cff;
            color: white;
            padding: 16px;
            font-weight: bold;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #chatHeader span {
            cursor: pointer;
            font-size: 24px;
            font-weight: normal;
        }

        /* Chat Body */
        #chatBody {
            flex: 1;
            padding: 16px;
            overflow-y: auto;
            background: #f9f9f9;
        }

        .message {
            margin-bottom: 12px;
            max-width: 80%;
            padding: 10px 14px;
            border-radius: 18px;
            line-height: 1.4;
            font-size: 14px;
            word-wrap: break-word;
            clear: both;
        }

        .user {
            background-color: #dcf8c6;
            align-self: flex-end;
            border-bottom-right-radius: 0;
            float: right;
        }

        .bot {
            background-color: #eeeeee;
            align-self: flex-start;
            border-bottom-left-radius: 0;
            float: left;
        }

        /* Buttons inside chat */
        #optionsContainer button {
            margin: 4px 6px 6px 0;
            padding: 8px 12px;
            border-radius: 12px;
            border: none;
            background-color: #4e8cff;
            color: white;
            cursor: pointer;
            font-size: 13px;
            transition: background-color 0.3s;
        }

        #optionsContainer button:hover {
            background-color: #3b6dd8;
        }

        .typing {
            font-style: italic;
            color: black;
            font-size: 13px;
            padding-left: 5px;
        }
    </style>
</head>

<body>
     <div class="info">
    <a href="#" class="d-block">SoftTechies</a>
    <a href="authentication/logout.php" class="d-block text-danger" style="font-size: 0.9rem;">
      <i class="fas fa-sign-out-alt"></i> Logout
    </a>
  </div>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?>!</h1>
    <p>You are logged in as a <strong>user</strong>.</p>

    <!-- Chat Bubble -->
    <div id="chatBubble" title="Chat with Bot">&#128172;</div>

    <!-- Chat Window -->
    <div id="chatWindow">
        <div id="chatHeader">
            🤖 ChatBot Guide
            <span id="closeChat">&times;</span>
        </div>
        <div id="chatBody">
            <div class="message bot">
                Select By Category:
                <div id="parentContainer"></div>
                <div id="optionsContainer"></div>
            </div>
        </div>
    </div>

    <script>
        const chatBubble = document.getElementById('chatBubble');
        const chatWindow = document.getElementById('chatWindow');
        const closeChat = document.getElementById('closeChat');
        const chatBody = document.getElementById('chatBody');
        const optionsContainer = document.getElementById('optionsContainer');
        const parentContainer = document.getElementById('parentContainer');

        // Show/hide chat
        chatBubble.addEventListener('click', () => {
            chatWindow.style.display = 'flex';
            chatBubble.style.display = 'none';
            chatBody.scrollTop = chatBody.scrollHeight;
        });
        closeChat.addEventListener('click', () => {
            chatWindow.style.display = 'none';
            chatBubble.style.display = 'flex';
        });

        // Append message to chat body
        function appendMessage(text, sender) {
            const msg = document.createElement('div');
            msg.className = `message ${sender}`;
            msg.textContent = text;
            chatBody.appendChild(msg);
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        // Show parent as buttons inside chat
        function showParent(parent) {
            parentContainer.innerHTML = '';
            parent.forEach(parent => {
                const btn = document.createElement('button');
                btn.textContent = parent;
                btn.addEventListener('click', () => {
                    userSelectOption(parent);
                });
                parentContainer.appendChild(btn);
            });
        }
        // Show optiopans as buttons inside chat
        function showOptions(options) {
            console.log("Options received:", options); // <-- Add this
            const optionsContainer = document.getElementById('optionsContainer');
            optionsContainer.innerHTML = '';

            options.forEach(item => {
                const label = item.option || item.description || 'Unnamed';
                const btn = document.createElement('button');
                btn.textContent = label;

                // Tooltip with parent or description
                const info = [];
                if (item.parent && item.parent !== label) info.push(`Parent: ${item.parent}`);
                if (item.description && item.description !== label) info.push(`Desc: ${item.description}`);
                btn.title = info.join(' | ');

                btn.addEventListener('click', () => {
                    userSelectOption(label);
                });

                btn.style.margin = '4px';
                btn.style.padding = '6px 12px';
                btn.style.border = '1px solid #ccc';
                btn.style.borderRadius = '8px';
                btn.style.cursor = 'pointer';
                btn.style.backgroundColor = '#501f14';

                optionsContainer.appendChild(btn);
            });
        }

        // On page load, fetch top-level titles from backend
        function fetchTitles() {
            fetch('../../../api/user/get_titles.php')
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        appendMessage('No categories found.', 'bot');
                        return;
                    }
                    // Check if data has distinct parents like "Male", "Female"
                    const parents = [...new Set(data.map(item => item.parent))];
                    if (parents.length > 1) {
                        // Top-level, show gender options
                        showOptions(parents.map(p => ({
                            option: p
                        })));
                    } else {
                        // Show child options (actual clothes or descriptions)
                        const formatted = data.map(title => ({
                            option: title
                        }));
                        showOptions(formatted);
                    }
                })
                .catch(() => {
                    appendMessage('Failed to load categories.', 'bot');
                });
        }

        // User clicks an option
        function userSelectOption(option) {
            appendMessage(option, 'user');
            optionsContainer.innerHTML = '';

            // Show loading message
            const loadingEl = document.createElement('div');
            loadingEl.className = 'message bot typing';
            loadingEl.textContent = 'Loading options...';
            chatBody.appendChild(loadingEl);
            chatBody.scrollTop = chatBody.scrollHeight;

            // Fetch children options for selected option
            fetch(`../../../api/user/get_tree_data.php?title=${encodeURIComponent(option)}`)
                .then(res => res.json())
                .then(data => {
                    loadingEl.remove();
                    if (data.length === 0) {
                        appendMessage('No further options available.', 'bot');
                        return;
                    } // If multiple parents found, show as category buttons (like Male/Female)
                    const uniqueParents = [...new Set(data.map(item => item.parent))];
                    const allItemsAreGroupedByParent = uniqueParents.length > 1;

                    if (allItemsAreGroupedByParent) {
                        const parentOptions = uniqueParents.map(parent => ({
                            option: parent
                        }));
                        appendMessage('Choose a category:', 'bot');
                        showOptions(parentOptions);
                    } else {
                        appendMessage('Please select an option:', 'bot');
                        showOptions(data); // Show full items (option/description/parent)
                    }
                })
                .catch(() => {
                    loadingEl.remove();
                    appendMessage('Failed to load options.', 'bot');
                });
        }

        // Initialize chat with top-level titles
        fetchTitles();
    </script>
</body>

</html>