
# 감정 분석 기반 AI 챗봇 시스템 (Ewha Green Edition)

> **이화여대 리눅스 미니 프로젝트 - 감정 기반 챗봇 시스템 개발**  
> 감정 분류, 음악 추천, 사용자별 감정 통계 시각화

---

## 프로젝트 개요

이 프로젝트는 이화여자대학교 리눅스 수업에서 수행한 미니 프로젝트로,  
**Ubuntu + Docker + PHP + MariaDB + Chart.js + AI**를 기반으로 한 감정 분석 챗봇 시스템입니다.

간단한 텍스트 입력을 통해 사용자 감정을 분석하고, 감정에 따라 적절한 챗봇 응답과 음악 추천을 제공하며,  
누적된 대화 기록을 기반으로 감정 통계를 시각화하여 제공합니다.

---

## 개발 환경

- **운영체제**: Ubuntu 24.04 (VirtualBox)
- **도커 환경**: Docker + Docker Compose
- **웹서버**: Apache + PHP 8.x
- **데이터베이스**: MariaDB 11.x
- **DB 관리도구**: phpMyAdmin (8081 포트)
- **프론트엔드**: HTML/CSS (이화그린 테마), Chart.js

---

## 프로젝트 특징

- **이화그린 테마**: 로그인/회원가입/대화/통계 등 전체 화면에 통일된 감성 적용
- **감정 분석 챗봇**: 텍스트 기반 감정 분류 → 감정별 응답 제공
- **음악 추천 기능**: 5턴 이상 대화 시 감정 기반 유튜브 음악 링크 추천
- **감정 통계 시각화**: 누적 대화를 바탕으로 사용자 감정 분포 확인 (Chart.js)
- **Docker 기반 배포**: 모든 기능을 컨테이너화하여 손쉬운 실행과 관리
- **확장 가능성 고려**: AI 모델 연동, 날씨 API, 대화 기억 기능 등 개발 계획 수립

---

## 주요 기능

| 기능 | 설명 |
|------|------|
| 로그인/회원가입 | 세션 기반 사용자 인증, 입력 검증 |
| 대화형 챗봇 | 키워드 기반 감정 분석 및 응답 |
| 음악 추천 | 감정별 유튜브 음악 추천 및 기억 기능 |
| 감정 통계 보기 | 사용자별 감정 비율을 파이차트로 시각화 |
| 이화그린 테마 | 통일된 디자인 및 말풍선 UI |
| 엔터 전송 지원 | Shift+Enter 줄바꿈, Enter 전송 |
| 회원가입 완료 화면 | 성공 안내 및 로그인 유도 인터페이스 |

---

## 개발 로드맵

| 단계 | 내용 | 상태 |
|------|------|------|
| 1단계 | 기본 챗봇 + 로그인/회원가입 + DB 구축 | ✅ 완료 |
| 2단계 | 말풍선 스타일 UI + 감정 통계 시각화 | ✅ 완료 |
| 3단계 | 회원가입 완료 안내 페이지 | ✅ 완료 |
| 4단계 | UI/UX 통일 (이화그린) | ✅ 완료 |
| 5단계 | README.md 포트폴리오 정비 | ✅ 완료 |
| 6단계 | Flask AI 감정 분석 모델 연동 | 🔜 예정 |
| 7단계 | 날씨 API 기반 추천 강화 | 🔜 예정 |
| 8단계 | 반응형 모바일 지원 | 🔜 예정 |

---

## 실행 방법 (Docker 기반)

```bash
git clone https://github.com/OhJisong/VM_Linux_chatbot-emotion_project.git
cd VM_Linux_chatbot-emotion_project
docker-compose up -d
```

- 웹페이지: http://localhost:8080  
- phpMyAdmin: http://localhost:8081

---

## 폴더 구조

```
.
├── web/                 # PHP 웹 파일 (index.php, login.php 등)
├── db/                  # MariaDB 초기 설정
├── db_data/             # DB 볼륨 데이터
├── ai/                  # 추후 AI 서버 연동 예정
├── docker-compose.yml
└── README.md
```

---

## 프로젝트 정보

- **수업명**: 리눅스 실습 프로젝트 (2025년 상반기)
- **작성자**: 오지송
- **완성일**: 2025년 06월 04일
- **저장소**: [https://github.com/OhJisong/VM_Linux_chatbot-emotion_project](https://github.com/OhJisong/VM_Linux_chatbot-emotion_project)

---
