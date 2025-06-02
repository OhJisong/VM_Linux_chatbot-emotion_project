#  [VM] Linux : chatbot-emotion project

 A complete chatbot system built in a **Linux Ubuntu VM** environment, integrating **PHP**, **MariaDB**, **Docker**, and a future extension with a custom **AI emotion analysis model**.

---

## Project Structure

```
chatbot-emotion_project/
├─ ai/                        # (To be implemented) Python AI emotion analysis server
├─ db/                        # MariaDB init scripts (optional)
├─ db_data/                  # Docker volume for DB persistence
├─ web/                       # PHP-based frontend and backend
│   ├─ index.php              # Main chat UI
│   ├─ chatbot.php            # AJAX endpoint with emotion analysis logic
│   ├─ emotion_stats.php       # JSON API for emotion chart
│   ├─ login.php / register.php # Auth system
│   ├─ functions.php          # Common DB/session logic
│   └─ style.css              # Basic styling (optional)
├─ docker-compose.yml          # Main Docker stack definition
├─ docker-compose.override.yml # Custom dev override (if needed)
├─ .gitignore
└─ README.md                # ← (You're reading this)
```

---

## Features

* PHP chatbot web interface
* Session-based login / register
* User-specific chat history & emotion tagging
* Simple emotion classification (keyword-based)
* Recommended music per emotion
* Docker-based isolation (MariaDB, Apache, phpMyAdmin)
* Emotion statistics chart (to be improved)
* \[Planned] AI-powered text emotion model (Python Flask via `ai/`)

---

## Quick Start

### 1. Run the stack

```bash
cd chatbot-emotion_project
docker-compose up -d
```

### 2. Access the app

* Web: [http://localhost:8080](http://localhost:8080)
* DB UI: [http://localhost:8081](http://localhost:8081)

### 3. Login Credentials (default)

* Create via **register.php** page
* Or access MariaDB via CLI or phpMyAdmin to create users

---

## Environment

| Component | Version                    |
| --------- | -------------------------- |
| OS        | Ubuntu 24.04 (VM)          |
| PHP       | 8.2 (Docker)               |
| MariaDB   | 11.x (Docker)              |
| Apache    | via `php:8.2-apache` image |
| Docker    | Installed                |
| AI model  | Planned (Python/Flask)  |

---

## Development Roadmap

## 🎓 Development Roadmap

-  [완료] PHP 기반 기본 챗봇 UI 구현
-  [완료] 로그인 / 회원가입 기능 (세션 기반)
-  [완료] 감정 분류 (키워드 기반 룰)
-  [완료] 마리아DB 연동 및 메시지 저장
-  [완료] 도커화 (PHP + Apache, MariaDB, phpMyAdmin 포함)
-  [완료] 사용자별 대화 이력 불러오기
-  [완료] 감정 기반 음악 추천
-  [진행 중] 감정 통계 차트 시각화 (Chart.js)
-  [계획] Flask 기반 AI 감정 분석 모델 개발 및 API 연동
-  [계획] 음악 추천 고도화 (유사 감정 반복 추천 로직 포함)
-  [계획] UI/UX 개선 (Bootstrap 기반)

---

## Sample Screenshot

> Insert screenshots of `index.php`, chatbot reply, chart, etc. here

---

## License

MIT License

---

## Author

> **Oh Jisong**
>
> * GitHub: [@OhJisong](https://github.com/OhJisong)
> * Built as part of a Linux-based AI chatbot project course

