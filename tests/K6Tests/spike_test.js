import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    stages: [
        { duration: '10s', target: 10 },  // Мал број корисници
        { duration: '5s', target: 100 },  // Нагло зголемување на корисници
        { duration: '10s', target: 10 },  // Намалување назад
    ],
};

export default function () {
    let res = http.get('http://localhost:8000/api/events');
    check(res, {
        'status is 200': (r) => r.status === 200,
        'response time is < 500ms': (r) => r.timings.duration < 500,
    });
    sleep(1);
}
