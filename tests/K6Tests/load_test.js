import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    vus: 10, // број на корисници (Virtual Users)
    duration: '10s', // времетраење на тестот
};

export default function () {
    let res = http.get('http://localhost:8000/api/events');
    check(res, {
        'status is 200': (r) => r.status === 200,
    });
    sleep(1); // пауза од 1 секунда помеѓу барањата
}
