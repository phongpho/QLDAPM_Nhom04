import os, sys, subprocess, zipfile
from datetime import date, timedelta
from PIL import Image

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
    if is_sum: continue
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

def render_html_page(mode, start_tid, end_tid):
    # mode: 'table_only', 'table_info', 'gantt'
    sub_tasks = {tid: t for tid, t in tasks.items() if start_tid <= tid <= end_tid}
    tids_ordered = sorted(sub_tasks.keys())
    
    show_gantt = (mode == 'gantt')
    
    # Calculate timeline bounds
    if show_gantt:
        min_date = min(t['start'] for t in sub_tasks.values())
        max_date = max(t['finish'] for t in sub_tasks.values())
        cal_start = min_date - timedelta(days=min_date.weekday())
        cal_end = max_date + timedelta(days=(6 - max_date.weekday()) + 7)
        day_width = 15.0 # px per day
        
        weeks = []
        days = []
        cur = cal_start
        cur_week = None
        while cur <= cal_end:
            if cur.weekday() == 0 or cur_week is None:
                if cur_week is not None:
                    weeks.append(cur_week)
                cur_week = {'label': cur.strftime("%b %d, '%y"), 'days': 0}
            cur_week['days'] += 1
            days.append({
                'date': cur,
                'label': cur.strftime('%a')[0],
                'is_weekend': cur.weekday() >= 5
            })
            cur += timedelta(days=1)
        if cur_week:
            weeks.append(cur_week)
    
    html = ['<!DOCTYPE html><html><head><meta charset="utf-8">']
    html.append('''
    <style>
      * { box-sizing: border-box; margin: 0; padding: 0; }
      body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; font-size: 11px; color: #1e293b; background: #fff; display: inline-block; }
      .wrapper { display: inline-flex; border: 1px solid #cbd5e1; background: #fff; }
      table.task-table { border-collapse: collapse; table-layout: fixed; }
      table.task-table th { background: #f8fafc; border-bottom: 2px solid #cbd5e1; border-right: 1px solid #e2e8f0; font-weight: 600; font-size: 11px; color: #475569; padding: 6px 8px; text-align: left; height: 38px; }
      table.task-table td { border-bottom: 1px solid #f1f5f9; border-right: 1px solid #e2e8f0; padding: 3px 6px; height: 23px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; }
      
      .col-id { width: 34px; text-align: center; color: #64748b; font-weight: 500; }
      .col-mode { width: 22px; text-align: center; }
      .col-name { width: 285px; }
      .col-dur { width: 65px; }
      .col-start { width: 95px; }
      .col-finish { width: 95px; }
      .col-pred { width: 85px; }
      .col-res { width: 175px; }
      
      .row-sum td { font-weight: 700; color: #0f172a; }
      .sub-indent { padding-left: 20px !important; color: #334155; }
      
      .gantt-container { display: flex; flex-direction: column; position: relative; border-left: 1px solid #cbd5e1; background: #fff; }
      .gantt-header-top { display: flex; height: 19px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
      .gantt-week { border-right: 1px solid #cbd5e1; font-size: 9.5px; font-weight: 600; color: #475569; padding-left: 3px; line-height: 19px; overflow: hidden; white-space: nowrap; }
      .gantt-header-bottom { display: flex; height: 18px; border-bottom: 2px solid #cbd5e1; background: #f8fafc; }
      .gantt-day-head { border-right: 1px solid #f1f5f9; font-size: 8.5px; text-align: center; line-height: 18px; color: #64748b; }
      .gantt-day-head.weekend { background: #f1f5f9; color: #94a3b8; }
      
      .gantt-body { position: relative; }
      .gantt-row { display: flex; height: 23px; border-bottom: 1px solid #f1f5f9; position: relative; align-items: center; }
      .gantt-cell { height: 100%; border-right: 1px solid #f8fafc; flex-shrink: 0; }
      .gantt-cell.weekend { background: #f8fafc; }
      
      .bar-task { position: absolute; height: 12px; background: #3b82f6; border: 1px solid #2563eb; border-radius: 2px; top: 5px; z-index: 2; }
      .bar-sum { position: absolute; height: 6px; background: #0f172a; top: 7px; z-index: 2; }
      .bar-sum::before { content: ""; position: absolute; left: 0; bottom: -4px; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #0f172a; }
      .bar-sum::after { content: ""; position: absolute; right: 0; bottom: -4px; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #0f172a; }
      
      .res-label { position: absolute; left: 100%; margin-left: 5px; font-size: 10px; color: #334155; white-space: nowrap; line-height: 12px; top: 0px; font-weight: 500; }
      svg.connectors { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; }
    </style>
    </head><body>
    <div class="wrapper">
    ''')
    
    # Left table
    html.append('<table class="task-table">')
    html.append('<thead><tr>')
    if mode == 'table_info':
        html.append('<th class="col-id" style="color:#0284c7;font-size:12px;">&#9432;</th>')
    else:
        html.append('<th class="col-id"></th>')
    if show_gantt:
        html.append('<th class="col-mode"></th>')
    html.append('<th class="col-name">Task Name</th>')
    html.append('<th class="col-dur">Duration</th>')
    html.append('<th class="col-start">Start</th>')
    html.append('<th class="col-finish">Finish</th>')
    html.append('<th class="col-pred">Predecessors</th>')
    if not show_gantt:
        html.append('<th class="col-res">Resource Names</th>')
    html.append('</tr></thead><tbody>')
    
    for tid in tids_ordered:
        t = sub_tasks[tid]
        row_cls = 'row-sum' if t['is_sum'] else 'row-task'
        name_cls = '' if t['is_sum'] else 'sub-indent'
        dur_str = f"{t['dur']} days"
        start_str = t['start'].strftime("%a %d/%m/%y")
        fin_str = t['finish'].strftime("%a %d/%m/%y")
        pred_str = ', '.join(map(str, t['preds'])) if t['preds'] else ''
        
        sum_icon = '&#9662;' if t['is_sum'] else ''
        mode_icon = '<span style="color:#2563eb;font-size:9px;">&#9646;</span>' if not t['is_sum'] else '<span style="color:#334155;font-size:10px;">&#9699;</span>'
        
        html.append(f'<tr class="{row_cls}">')
        html.append(f'<td class="col-id">{tid}</td>')
        if show_gantt:
            html.append(f'<td class="col-mode">{mode_icon}</td>')
        html.append(f'<td class="col-name {name_cls}">{sum_icon} {t["name"]}</td>')
        html.append(f'<td class="col-dur">{dur_str}</td>')
        html.append(f'<td class="col-start">{start_str}</td>')
        html.append(f'<td class="col-finish">{fin_str}</td>')
        html.append(f'<td class="col-pred">{pred_str}</td>')
        if not show_gantt:
            html.append(f'<td class="col-res">{t["res"]}</td>')
        html.append('</tr>')
        
    html.append('</tbody></table>')
    
    # Right gantt
    if show_gantt:
        html.append('<div class="gantt-container">')
        html.append('<div class="gantt-header-top">')
        for w in weeks:
            w_px = w['days'] * day_width
            html.append(f'<div class="gantt-week" style="width:{w_px}px;">{w["label"]}</div>')
        html.append('</div>')
        
        html.append('<div class="gantt-header-bottom">')
        for d in days:
            cls = 'weekend' if d['is_weekend'] else ''
            html.append(f'<div class="gantt-day-head {cls}" style="width:{day_width}px;">{d["label"]}</div>')
        html.append('</div>')
        
        html.append('<div class="gantt-body">')
        
        # Connectors SVG
        svg_lines = []
        tid_to_rowidx = {tid: idx for idx, tid in enumerate(tids_ordered)}
        row_h = 23
        
        for tid in tids_ordered:
            t = sub_tasks[tid]
            if t['preds']:
                r2 = tid_to_rowidx[tid]
                y2 = r2 * row_h + row_h / 2
                x2 = (t['start'] - cal_start).days * day_width
                
                for p in t['preds']:
                    if p in tid_to_rowidx:
                        r1 = tid_to_rowidx[p]
                        y1 = r1 * row_h + row_h / 2
                        x1 = ((tasks[p]['finish'] - cal_start).days + 1) * day_width
                        
                        # Route line: x1,y1 -> x1+4,y1 -> x1+4,y2 -> x2,y2
                        mid_x = x1 + 4
                        svg_lines.append(f'<path d="M {x1} {y1} L {mid_x} {y1} L {mid_x} {y2} L {x2} {y2}" fill="none" stroke="#64748b" stroke-width="1"/>')
                        # Arrow head
                        svg_lines.append(f'<polygon points="{x2-3},{y2-2.5} {x2},{y2} {x2-3},{y2+2.5}" fill="#64748b"/>')
                        
        html.append(f'<svg class="connectors">{"".join(svg_lines)}</svg>')
        
        # Rows
        for tid in tids_ordered:
            t = sub_tasks[tid]
            html.append('<div class="gantt-row">')
            for d in days:
                cls = 'weekend' if d['is_weekend'] else ''
                html.append(f'<div class="gantt-cell {cls}" style="width:{day_width}px;"></div>')
            
            offset_days = (t['start'] - cal_start).days
            span_days = (t['finish'] - t['start']).days + 1
            left_px = offset_days * day_width
            width_px = max(span_days * day_width - 1, 4)
            
            if t['is_sum']:
                html.append(f'<div class="bar-sum" style="left:{left_px}px;width:{width_px}px;"></div>')
            else:
                res_txt = t['res'] if t['res'] else ''
                html.append(f'<div class="bar-task" style="left:{left_px}px;width:{width_px}px;"><span class="res-label">{res_txt}</span></div>')
            html.append('</div>')
            
        html.append('</div>') # end gantt-body
        html.append('</div>') # end gantt-container
        
    html.append('</div></body></html>')
    return '\n'.join(html)

def capture_and_crop(html_path, out_png_path, win_w=1800, win_h=1200):
    edge_exe = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
    raw_png = out_png_path + '.raw.png'
    cmd = [
        edge_exe,
        "--headless=new",
        "--disable-gpu",
        f"--window-size={win_w},{win_h}",
        f"--screenshot={raw_png}",
        html_path
    ]
    subprocess.run(cmd, check=True)
    
    im = Image.open(raw_png)
    # Find bounding box of content (wrapper border)
    w, h = im.size
    min_x, min_y, max_x, max_y = w, h, 0, 0
    # scan for non-pure-white pixels
    pix = im.load()
    for y in range(h):
        for x in range(w):
            r, g, b = pix[x, y][:3]
            if r < 250 or g < 250 or b < 250:
                if x < min_x: min_x = x
                if x > max_x: max_x = x
                if y < min_y: min_y = y
                if y > max_y: max_y = y
    
    # Add 2px border margin
    crop_box = (max(0, min_x - 1), max(0, min_y - 1), min(w, max_x + 2), min(h, max_y + 2))
    cropped = im.crop(crop_box)
    cropped.save(out_png_path)
    os.remove(raw_png)
    print(f"Rendered: {out_png_path} (size: {cropped.size})")

if __name__ == '__main__':
    work_dir = r"c:\xampp\htdocs\QLDAPM26\QLDAPM_Nhom04"
    img_dir = os.path.join(work_dir, "new_gantt_images")
    os.makedirs(img_dir, exist_ok=True)
    
    # 1. image3.png (Hình 3: Lịch biểu chi tiết - Table all 34 tasks)
    h3_path = os.path.join(work_dir, "h3.html")
    with open(h3_path, "w", encoding="utf-8") as f:
        f.write(render_html_page("table_only", 1, 34))
    capture_and_crop(h3_path, os.path.join(img_dir, "image3.png"), 1200, 1100)
    
    # 2. image4.png (Hình 4: Gantt GĐ 1.0 - 3.0, tasks 1-12)
    h4_path = os.path.join(work_dir, "h4.html")
    with open(h4_path, "w", encoding="utf-8") as f:
        f.write(render_html_page("gantt", 1, 12))
    capture_and_crop(h4_path, os.path.join(img_dir, "image4.png"), 1600, 500)
    
    # 3. image5.png (Hình 5: Gantt GĐ 4.0 - 6.0, tasks 13-34)
    h5_path = os.path.join(work_dir, "h5.html")
    with open(h5_path, "w", encoding="utf-8") as f:
        f.write(render_html_page("gantt", 13, 34))
    capture_and_crop(h5_path, os.path.join(img_dir, "image5.png"), 1800, 800)
    
    # 4. image6.png (Hình 6: Phân bổ tài nguyên theo thời gian - Table info all 34 tasks)
    h6_path = os.path.join(work_dir, "h6.html")
    with open(h6_path, "w", encoding="utf-8") as f:
        f.write(render_html_page("table_info", 1, 34))
    capture_and_crop(h6_path, os.path.join(img_dir, "image6.png"), 1200, 1100)
    
    # 5. image8.png (Hình 8: Gantt tổng thể tasks 1-34)
    h8_path = os.path.join(work_dir, "h8.html")
    with open(h8_path, "w", encoding="utf-8") as f:
        f.write(render_html_page("gantt", 1, 34))
    capture_and_crop(h8_path, os.path.join(img_dir, "image8.png"), 2200, 1100)

    # Copy to user Downloads folder as well
    user_dl_dir = r"C:\Users\Quân\Downloads\Gantt_Images_New"
    os.makedirs(user_dl_dir, exist_ok=True)
    for fname in ["image3.png", "image4.png", "image5.png", "image6.png", "image8.png"]:
        src = os.path.join(img_dir, fname)
        dst = os.path.join(user_dl_dir, fname)
        with open(src, "rb") as fin, open(dst, "wb") as fout:
            fout.write(fin.read())
    print(f"All images saved to {user_dl_dir}")
    
    # Now pack into docx
    for docx_path in [
        r"C:\Users\Quân\Downloads\BCKeHoach_Nhom04_CapNhat.docx",
        r"c:\xampp\htdocs\QLDAPM26\QLDAPM_Nhom04\BCKeHoach_Nhom04_CapNhat.docx"
    ]:
        with zipfile.ZipFile(docx_path, 'r') as zin:
            fdict = {item.filename: zin.read(item.filename) for item in zin.infolist()}
            
        for fname in ["image3.png", "image4.png", "image5.png", "image6.png", "image8.png"]:
            with open(os.path.join(img_dir, fname), "rb") as fin:
                fdict[f"word/media/{fname}"] = fin.read()
                
        with zipfile.ZipFile(docx_path, 'w', zipfile.ZIP_DEFLATED) as zout:
            for fname, data in fdict.items():
                zout.writestr(fname, data)
        print(f"Updated docx with new images: {docx_path}")
