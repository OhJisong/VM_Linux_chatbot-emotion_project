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
    <title>AI 감정 챗봇 🎵</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="chat-container">

        <!-- ✅ 로그아웃 버튼 -->
        <div style="text-align: right; margin-bottom: 10px;">
            <a href="logout.php"><button class="btn">로그아웃</button></a>
        </div>

        <h2><?= htmlspecialchars($_SESSION['nickname']) ?>님의 AI 챗봇 🎵</h2>
        <p><strong><?= htmlspecialchars($_SESSION['nickname']) ?>님, 환영합니다!</strong></p>

        <div id="chat-box"></div>

        <!-- 채팅 입력 폼 -->
        <form id="chat-form" style="display: flex; margin-top: 10px;">
            <input type="text" id="user-input" name="message" class="chat-input" placeholder="대화를 입력하세요..." required>
            <button class="submit-btn" type="submit">전송</button>
        </form>

        <button onclick="showStats()" class="submit-btn" style="margin-top: 15px;">🧠 감정 통계 보기</button>
        <canvas id="emotionChart" width="400" height="400" style="display:none; margin-top:20px;"></canvas>
    </div>

    <script>
        const recentMessages = <?= json_encode($recentMessages) ?>;
        const chatBox = document.getElementById('chat-box');
        const input = document.getElementById('user-input');
        const form = document.getElementById('chat-form');

        // ✅ 채팅 전송 함수
        async function submitChat() {
            const message = input.value;
            if (!message.trim()) return;

            input.value = '';

            const userDiv = document.createElement('div');
            userDiv.classList.add('user-message');
            userDiv.textContent = message;
            chatBox.appendChild(userDiv);

            const res = await fetch('chatbot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(message)
            });

            const data = await res.json();
            const botDiv = document.createElement('div');
            botDiv.classList.add('bot-message');
            botDiv.textContent = data.reply;
            chatBox.appendChild(botDiv);

            if (data.done) {
                const musicDiv = document.createElement('div');
                musicDiv.classList.add('bot-message');
                musicDiv.innerHTML = `<strong>🎵 추천 음악: <a href="${data.music}" target="_blank" style="color: white;">${data.music}</a></strong>`;
                chatBox.appendChild(musicDiv);
            }

            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // ✅ Enter 또는 버튼 클릭 시 동일 동작
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            submitChat();
        });

        input.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                submitChat();
            }
        });

        // 지난 대화 출력
        window.addEventListener('DOMContentLoaded', () => {
            recentMessages.forEach(msg => {
                const div = document.createElement('div');
                div.classList.add(msg.role === 'user' ? 'user-message' : 'bot-message');
                div.textContent = msg.message;
                chatBox.appendChild(div);
            });

            const greeting = document.createElement('div');
            greeting.classList.add('bot-message');
            greeting.textContent = "안녕 <?= htmlspecialchars($_SESSION['nickname']) ?>! 지난 얘기 이어서 해볼까?";
            chatBox.appendChild(greeting);
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
                    responsive: true
                }
            });
        }
    </script>
</body>
</html>

