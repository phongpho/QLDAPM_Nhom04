from datetime import date, timedelta
import sys
sys.stdout.reconfigure(encoding='utf-8')

def is_workday(d):
    if d.weekday() >= 5: return False
    if d == date(2027, 1, 1): return False
    return True

def add_workdays(start_date, n_days):
    cur = start_date
    count = 1
    while count < n_days:
        cur += timedelta(days=1)
        if is_workday(cur):
            count += 1
    return cur

def next_workday(d):
    cur = d + timedelta(days=1)
    while not is_workday(cur):
        cur += timedelta(days=1)
    return cur

tasks_def = [
    (1, '1.0 Phân tích yêu cầu phần mềm', 7, None, '', True),
    (2, '1.1 Nghiên cứu khả thi, tham khảo website tương tự', 3, None, 'Phong, Thuận', False),
    (3, '1.2 Thu thập và phân tích yêu cầu', 4, None, 'Nghi, Quân', False),
    (4, '1.3 Đặc tả yêu cầu và xác định phạm vi', 4, [2], 'Phong, Thuận', False),
    (5, '2.0 Phân tích hệ thống', 7, None, '', True),
    (6, '2.1 Xác định yêu cầu hệ thống', 3, [3], 'Nghi, Quân', False),
    (7, '2.2 Mô hình hóa yêu cầu (Use case, DFD, ERD)', 4, [4, 6], 'Phong, Nghi, Thuận, Quân', False),
    (8, '3.0 Thiết kế hệ thống', 13, None, '', True),
    (9, '3.1 Thiết kế kiến trúc', 3, [7], 'Phong, Nghi', False),
    (10, '3.2 Thiết kế giao diện (UI/UX)', 9, [7], 'Thuận, Quân', False),
    (11, '3.3 Thiết kế cơ sở dữ liệu', 6, [9], 'Phong, Nghi', False),
    (12, '3.4 Thiết kế lớp và phương thức', 4, [10, 11], 'Phong, Nghi, Thuận, Quân', False),
    (13, '4.0 Cài đặt hệ thống', 30, None, '', True),
    (14, '4.1 Xây dựng cơ sở dữ liệu', 3, [12], 'Phong, Nghi', False),
    (15, '4.2 Xây dựng giao diện chính', 7, [12], 'Thuận, Quân', False),
    (16, '4.3 Module 1: Quản trị (Admin) và thống kê báo cáo', 24, [14], 'Phong', False),
    (17, '4.4 Module 2: Người dùng (tài khoản, hồ sơ thể trạng)', 10, [15], 'Quân', False),
    (18, '4.5 Module 3: Hiển thị sản phẩm', 12, [14], 'Nghi', False),
    (19, '4.6 Module 4: Tính chỉ số cơ thể và gợi ý cá nhân hóa', 11, [15], 'Thuận', False),
    (20, '4.7 Module 5: Tìm kiếm và bộ lọc nâng cao', 10, [17], 'Quân', False),
    (21, '4.8 Module 6: Giỏ hàng và thanh toán', 12, [18], 'Nghi', False),
    (22, '4.9 Module 7: Đánh giá và phản hồi', 9, [19], 'Thuận', False),
    (23, '4.10 Tích hợp hệ thống', 3, [16, 20, 21, 22], 'Phong, Nghi, Thuận, Quân', False),
    (24, '5.0 Kiểm thử hệ thống', 10, None, '', True),
    (25, '5.1 Kiểm thử đơn vị', 3, [23], 'Phong, Nghi', False),
    (26, '5.2 Kiểm thử chức năng', 6, [23], 'Thuận, Quân', False),
    (27, '5.3 Kiểm thử tích hợp', 4, [25], 'Phong, Nghi', False),
    (28, '5.4 Kiểm thử chấp nhận', 3, [27], 'Phong, Nghi', False),
    (29, '5.5 Kiểm thử hiệu năng và bảo mật', 4, [26], 'Thuận, Quân', False),
    (30, '6.0 Triển khai và phát hành', 7, None, '', True),
    (31, '6.1 Triển khai website', 3, [28, 29], 'Phong, Thuận', False),
    (32, '6.2 Xây dựng tài liệu hướng dẫn sử dụng', 5, [28, 29], 'Nghi, Quân', False),
    (33, '6.3 Bảo trì và cập nhật sau triển khai', 2, [31], 'Phong, Thuận', False),
    (34, '6.4 Báo cáo tổng kết', 2, [32, 33], 'Phong, Nghi, Thuận, Quân', False),
]

tasks = {}
proj_start = date(2026, 9, 28)

for tid, name, dur, preds, res, is_sum in tasks_def:
    if is_sum:
        continue
    if not preds:
        t_start = proj_start
    else:
        latest_finish = max(tasks[p]['finish'] for p in preds)
        t_start = next_workday(latest_finish)
    t_finish = add_workdays(t_start, dur)
    tasks[tid] = {'id': tid, 'name': name, 'dur': dur, 'start': t_start, 'finish': t_finish, 'preds': preds, 'res': res, 'is_sum': False}

summary_map = {
    1: [2, 3, 4],
    5: [6, 7],
    8: [9, 10, 11, 12],
    13: list(range(14, 24)),
    24: list(range(25, 30)),
    30: list(range(31, 35))
}
for sid, children in summary_map.items():
    s_start = min(tasks[c]['start'] for c in children)
    s_finish = max(tasks[c]['finish'] for c in children)
    d = s_start
    cnt = 0
    while d <= s_finish:
        if is_workday(d): cnt += 1
        d += timedelta(days=1)
    name = [t[1] for t in tasks_def if t[0] == sid][0]
    tasks[sid] = {'id': sid, 'name': name, 'dur': cnt, 'start': s_start, 'finish': s_finish, 'preds': None, 'res': '', 'is_sum': True}

for tid in sorted(tasks.keys()):
    t = tasks[tid]
    p_str = ','.join(map(str, t['preds'])) if t['preds'] else ''
    sum_mark = '[SUM]' if t['is_sum'] else '     '
    print(f"{t['id']:2d} {sum_mark} {t['name']:48s} | {t['dur']:2d}d | {t['start'].strftime('%a %d/%m/%y')} | {t['finish'].strftime('%a %d/%m/%y')} | {p_str:12s} | {t['res']}")
