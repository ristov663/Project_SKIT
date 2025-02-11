import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '2m', target: 50 },  // Постепено зголемување на 50 VUs
        { duration: '30m', target: 50 }, // Одржување на 50 VUs за 30 минути
        { duration: '2m', target: 0 },   // Намалување назад на 0
    ],
};

export default function () {
    let res = http.get('http://localhost:8000/api/events');
    check(res, {
        'status is 200': (r) => r.status === 200,
        'response time is < 1000ms': (r) => r.timings.duration < 1000,
    });
    sleep(1);
}
