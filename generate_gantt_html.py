import os, sys
from datetime import date, timedelta
from calc_schedule import tasks, tasks_def, is_workday

def generate_gantt_html(mode='full', start_tid=1, end_tid=34):
    # Filter tasks
    sub_tasks = {tid: t for tid, t in tasks.items() if start_tid <= tid <= end_tid}
    
    # Calculate date range for timeline
    min_date = min(t['start'] for t in sub_tasks.values())
    max_date = max(t['finish'] for t in sub_tasks.values())
    
    # Align min_date to Monday of that week
    cal_start = min_date - timedelta(days=min_date.weekday())
    # Align max_date to Sunday of that week + 1 week margin
    cal_end = max_date + timedelta(days=(6 - max_date.weekday()) + 7)
    
    total_days = (cal_end - cal_start).days + 1
    day_width = 16 # px per day
    
    # Generate days and weeks
    weeks = []
    days = []
    cur = cal_start
    cur_week = None
    week_day_count = 0
    
    while cur <= cal_end:
        if cur.weekday() == 0 or cur_week is None:
            if cur_week is not None:
                weeks.append(cur_week)
            cur_week = {'label': cur.strftime("%b %d, '%y"), 'days': 0}
        cur_week['days'] += 1
        days.append({
            'date': cur,
            'label': cur.strftime('%a')[0], # M, T, W, T, F, S, S
            'is_weekend': cur.weekday() >= 5
        })
        cur += timedelta(days=1)
    if cur_week:
        weeks.append(cur_week)
        
    # Build HTML
    html = ['''<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: "Segoe UI", -apple-system, BlinkMacSystemFont, Roboto, sans-serif; font-size: 11.5px; color: #1e293b; background: #fff; }
  .wrapper { display: inline-flex; border: 1px solid #cbd5e1; background: #fff; }
  table.task-table { border-collapse: collapse; table-layout: fixed; }
  table.task-table th { background: #f8fafc; border-bottom: 2px solid #cbd5e1; border-right: 1px solid #e2e8f0; font-weight: 600; font-size: 11px; color: #475569; padding: 5px 8px; text-align: left; height: 38px; }
  table.task-table td { border-bottom: 1px solid #f1f5f9; border-right: 1px solid #e2e8f0; padding: 3px 6px; height: 24px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; }
  
  .col-id { width: 32px; text-align: center; color: #64748b; }
  .col-mode { width: 24px; text-align: center; }
  .col-name { width: 260px; }
  .col-dur { width: 62px; text-align: left; }
  .col-start { width: 88px; }
  .col-finish { width: 88px; }
  .col-pred { width: 80px; }
  .col-res { width: 150px; }
  
  .row-sum td { font-weight: 700; color: #0f172a; }
  .sub-indent { padding-left: 20px !important; }
  
  /* Gantt area */
  .gantt-container { display: flex; flex-direction: column; position: relative; border-left: 2px solid #cbd5e1; overflow: hidden; }
  .gantt-header-top { display: flex; height: 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
  .gantt-week { border-right: 1px solid #cbd5e1; font-size: 10px; font-weight: 600; color: #475569; padding-left: 4px; line-height: 20px; overflow: hidden; white-space: nowrap; }
  .gantt-header-bottom { display: flex; height: 18px; border-bottom: 2px solid #cbd5e1; background: #f8fafc; }
  .gantt-day-head { width: 16px; border-right: 1px solid #f1f5f9; font-size: 9px; text-align: center; line-height: 18px; color: #64748b; }
  .gantt-day-head.weekend { background: #f1f5f9; color: #94a3b8; }
  
  .gantt-body { position: relative; }
  .gantt-row { display: flex; height: 24px; border-bottom: 1px solid #f1f5f9; position: relative; align-items: center; }
  .gantt-cell { width: 16px; height: 100%; border-right: 1px solid #f8fafc; flex-shrink: 0; }
  .gantt-cell.weekend { background: #f8fafc; }
  
  .bar-task { position: absolute; height: 13px; background: #3b82f6; border: 1px solid #2563eb; border-radius: 2px; top: 5.5px; z-index: 2; box-shadow: 0 1px 2px rgba(0,0,0,0.08); }
  .bar-sum { position: absolute; height: 6px; background: #0f172a; top: 7px; z-index: 2; }
  .bar-sum::before { content: ""; position: absolute; left: 0; bottom: -4px; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #0f172a; }
  .bar-sum::after { content: ""; position: absolute; right: 0; bottom: -4px; width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid #0f172a; }
  
  .res-label { position: absolute; left: 100%; margin-left: 6px; font-size: 10.5px; color: #334155; white-space: nowrap; line-height: 14px; top: -1px; font-weight: 500; }
  
  /* SVG connector overlay */
  svg.connectors { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; }
</style>
</head>
<body>
<div class="wrapper">
''']
    
    # Table part
    show_gantt = (mode != 'table_only')
    html.append('<table class="task-table">')
    html.append('<thead><tr>')
    html.append('<th class="col-id"></th>')
    if show_gantt:
        html.append('<th class="col-mode"></th>')
    html.append('<th class="col-name">Task Name</th>')
    html.append('<th class="col-dur">Duration</th>')
    html.append('<th class="col-start">Start</th>')
    html.append('<th class="col-finish">Finish</th>')
    html.append('<th class="col-pred">Predecessors</th>')
    if not show_gantt or mode == 'res_gantt':
        html.append('<th class="col-res">Resource Names</th>')
    html.append('</tr></thead>')
    html.append('<tbody>')
    
    for tid in sorted(sub_tasks.keys()):
        t = sub_tasks[tid]
        row_cls = 'row-sum' if t['is_sum'] else 'row-task'
        name_cls = '' if t['is_sum'] else 'sub-indent'
        dur_str = f"{t['dur']} days"
        start_str = t['start'].strftime("%a %d/%m/%y")
        fin_str = t['finish'].strftime("%a %d/%m/%y")
        pred_str = ','.join(map(str, t['preds'])) if t['preds'] else ''
        
        sum_icon = '&#9662;' if t['is_sum'] else ''
        task_mode_icon = '<span style="color:#2563eb;font-size:10px;">&#9646;</span>' if not t['is_sum'] else '<span style="color:#475569;font-size:11px;">&#9699;</span>'
        
        html.append(f'<tr class="{row_cls}">')
        html.append(f'<td class="col-id">{tid}</td>')
        if show_gantt:
            html.append(f'<td class="col-mode">{task_mode_icon}</td>')
        html.append(f'<td class="col-name {name_cls}">{sum_icon} {t["name"]}</td>')
        html.append(f'<td class="col-dur">{dur_str}</td>')
        html.append(f'<td class="col-start">{start_str}</td>')
        html.append(f'<td class="col-finish">{fin_str}</td>')
        html.append(f'<td class="col-pred">{pred_str}</td>')
        if not show_gantt or mode == 'res_gantt':
            html.append(f'<td class="col-res">{t["res"]}</td>')
        html.append('</tr>')
        
    html.append('</tbody></table>')
    
    # Gantt timeline part
    if show_gantt:
        html.append('<div class="gantt-container">')
        # Weeks header
        html.append('<div class="gantt-header-top">')
        for w in weeks:
            w_px = w['days'] * day_width
            html.append(f'<div class="gantt-week" style="width:{w_px}px;">{w["label"]}</div>')
        html.append('</div>')
        
        # Days header
        html.append('<div class="gantt-header-bottom">')
        for d in days:
            cls = 'weekend' if d['is_weekend'] else ''
            html.append(f'<div class="gantt-day-head {cls}">{d["label"]}</div>')
        html.append('</div>')
        
        # Gantt rows
        html.append('<div class="gantt-body">')
        # Background cells
        for tid in sorted(sub_tasks.keys()):
            t = sub_tasks[tid]
            html.append('<div class="gantt-row">')
            for d in days:
                cls = 'weekend' if d['is_weekend'] else ''
                html.append(f'<div class="gantt-cell {cls}"></div>')
            
            # Position of bar
            offset_days = (t['start'] - cal_start).days
            span_days = (t['finish'] - t['start']).days + 1
            left_px = offset_days * day_width
            width_px = max(span_days * day_width - 2, 4)
            
            if t['is_sum']:
                html.append(f'<div class="bar-sum" style="left:{left_px}px;width:{width_px}px;"></div>')
            else:
                res_txt = t['res'] if t['res'] else ''
                html.append(f'<div class="bar-task" style="left:{left_px}px;width:{width_px}px;"><span class="res-label">{res_txt}</span></div>')
            html.append('</div>')
            
        html.append('</div>') # end gantt-body
        html.append('</div>') # end gantt-container
        
    html.append('</div>') # end wrapper
    html.append('</body></html>')
    return '\n'.join(html)

if __name__ == '__main__':
    # Test generation of image3, image4, image5, image8
    with open('render_test.html', 'w', encoding='utf-8') as f:
        f.write(generate_gantt_html('gantt', 1, 12))
    print('Generated render_test.html')
