<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// 최근 대화 5개 불러오기
$pdo = getDbConnection();
$stmt = $pdo->prepare("SELECT role, message FROM messages WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$recentMessages = array_reverse($stmt->fetchAll());
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>지송이의 AI 챗봇 🎵</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 20px;
        }
        #chat-box {
            background: white;
            border: 1px solid #ccc;
            width: 100%;
            max-width: 600px;
            height: 350px;
            overflow-y: scroll;
            padding: 10px;
            margin-bottom: 10px;
        }
        #chat-box div {
            margin-bottom: 5px;
        }
        #user-input {
            width: 400px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>지송이의 AI 챗봇 🎵</h2>
    <p><strong><?= htmlspecialchars($_SESSION['nickname']) ?>님, 환영합니다!</strong></p>

    <div id="chat-box"></div>

    <form id="chat-form">
        <input type="text" id="user-input" name="message" placeholder="대화를 입력하세요..." required>
        <button type="submit">전송</button>
    </form>

    <button onclick="showStats()">🧠 감정 통계 보기</button>
    <canvas id="emotionChart" width="400" height="400" style="display:none; margin-top:20px;"></canvas>

    <script>
        const recentMessages = <?= json_encode($recentMessages) ?>;
        const chatBox = document.getElementById('chat-box');

        // 지난 대화 출력
        window.addEventListener('DOMContentLoaded', () => {
            recentMessages.forEach(msg => {
                const div = document.createElement('div');
                div.textContent = (msg.role === 'user' ? "나: " : "챗봇: ") + msg.message;
                chatBox.appendChild(div);
            });

            const greeting = document.createElement('div');
            greeting.textContent = "챗봇: 안녕 <?= htmlspecialchars($_SESSION['nickname']) ?>! 지난 얘기 이어서 해볼까?";
            chatBox.appendChild(greeting);
        });

        // 채팅 전송
        document.getElementById('chat-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const input = document.getElementById('user-input');
            const message = input.value;
            input.value = '';

            const userDiv = document.createElement('div');
            userDiv.textContent = "나: " + message;
            chatBox.appendChild(userDiv);

            const res = await fetch('chatbot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(message)
            });

            const data = await res.json();
            const botDiv = document.createElement('div');
            botDiv.textContent = "챗봇: " + data.reply;
            chatBox.appendChild(botDiv);

            if (data.done) {
                const musicDiv = document.createElement('div');
                musicDiv.innerHTML = `<strong>🎵 추천 음악: <a href="${data.music}" target="_blank">${data.music}</a></strong>`;
                chatBox.appendChild(musicDiv);
            }

            chatBox.scrollTop = chatBox.scrollHeight;
        });

        // 감정 통계 차트
        async function showStats() {
            const res = await fetch('emotion_stats.php');
            const data = await res.json();
            const labels = Object.keys(data);
            const values = Object.values(data);

            const ctx = document.getElementById('emotionChart').getContext('2d');
            document.getElementById('emotionChart').style.display = 'block';

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '감정 비율',
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',   // 슬픔
                            'rgba(54, 162, 235, 0.6)',   // 기쁨
                            'rgba(255, 206, 86, 0.6)',   // 분노
                            'rgba(75, 192, 192, 0.6)',   // 불안
                            'rgba(153, 102, 255, 0.6)'   // 중립
                        ]
                    }]
                },
                options: {
                    responsive: false
                }
            });
        }
    </script>
</body>
</html>

