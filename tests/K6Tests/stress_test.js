import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '2m', target: 50 },  // Постепено зголемување на 50 VUs за 2 минути
        { duration: '3m', target: 100 }, // Одржување на 100 VUs за 3 минути
        { duration: '2m', target: 200 }, // Зголемување на 200 VUs за 2 минути
        { duration: '5m', target: 200 }, // Одржување на 200 VUs за 5 минути
        { duration: '2m', target: 0 },   // Намалување на оптоварувањето
    ],
};

export default function () {
    let res = http.get('http://localhost:8000/api/events');
    check(res, {
        'status is 200': (r) => r.status === 200,
        'response time < 500ms': (r) => r.timings.duration < 500,
    });
    sleep(1);
}
