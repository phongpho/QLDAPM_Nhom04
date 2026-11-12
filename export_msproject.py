import sys
from datetime import date, datetime, timedelta

def generate_msproject_xml(tasks, output_path):
    # MS Project 2010/2013/2016/2019 XML Schema
    xml = ['<?xml version="1.0" encoding="UTF-8" standalone="yes"?>']
    xml.append('<Project xmlns="http://schemas.microsoft.com/project">')
    xml.append('  <Name>KeHoach_GymBro_Nhom04</Name>')
    xml.append('  <Title>Website ban do tap gym va san pham bo tro ca nhan hoa</Title>')
    xml.append('  <StartDate>2026-09-28T08:00:00</StartDate>')
    xml.append('  <FinishDate>2027-01-05T17:00:00</FinishDate>')
    xml.append('  <ScheduleFromStart>1</ScheduleFromStart>')
    xml.append('  <CalendarUID>1</CalendarUID>')
    xml.append('  <DefaultStartTime>08:00:00</DefaultStartTime>')
    xml.append('  <DefaultEndTime>17:00:00</DefaultEndTime>')
    xml.append('  <MinutesPerDay>480</MinutesPerDay>')
    xml.append('  <MinutesPerWeek>2400</MinutesPerWeek>')
    xml.append('  <DaysPerMonth>20</DaysPerMonth>')
    
    # Calendars
    xml.append('  <Calendars>')
    xml.append('    <Calendar>')
    xml.append('      <UID>1</UID>')
    xml.append('      <Name>Standard</Name>')
    xml.append('      <IsBaseCalendar>1</IsBaseCalendar>')
    xml.append('      <WeekDays>')
    # Sunday=1 (non-working), Monday=2 (working)... Saturday=7 (non-working)
    for day_type in range(1, 8):
        xml.append('        <WeekDay>')
        xml.append(f'          <DayType>{day_type}</DayType>')
        if day_type in [1, 7]:
            xml.append('          <DayWorking>0</DayWorking>')
        else:
            xml.append('          <DayWorking>1</DayWorking>')
            xml.append('          <WorkingTimes>')
            xml.append('            <WorkingTime><FromTime>08:00:00</FromTime><ToTime>12:00:00</ToTime></WorkingTime>')
            xml.append('            <WorkingTime><FromTime>13:00:00</FromTime><ToTime>17:00:00</ToTime></WorkingTime>')
            xml.append('          </WorkingTimes>')
        xml.append('        </WeekDay>')
    xml.append('      </WeekDays>')
    xml.append('    </Calendar>')
    xml.append('  </Calendars>')
    
    # Tasks
    xml.append('  <Tasks>')
    for tid in sorted(tasks.keys()):
        t = tasks[tid]
        s_iso = t['start'].strftime('%Y-%m-%dT08:00:00')
        f_iso = t['finish'].strftime('%Y-%m-%dT17:00:00')
        dur_h = t['dur'] * 8
        is_sum = 1 if t['is_sum'] else 0
        outline_lvl = 1 if t['is_sum'] else 2
        
        xml.append('    <Task>')
        xml.append(f'      <UID>{tid}</UID>')
        xml.append(f'      <ID>{tid}</ID>')
        xml.append(f'      <Name>{t["name"]}</Name>')
        xml.append(f'      <Active>1</Active>')
        xml.append(f'      <Manual>0</Manual>')
        xml.append(f'      <Summary>{is_sum}</Summary>')
        xml.append(f'      <OutlineLevel>{outline_lvl}</OutlineLevel>')
        xml.append(f'      <Duration>PT{dur_h}H0M0S</Duration>')
        xml.append(f'      <Start>{s_iso}</Start>')
        xml.append(f'      <Finish>{f_iso}</Finish>')
        if t['preds']:
            for p in t['preds']:
                xml.append('      <PredecessorLink>')
                xml.append(f'        <PredecessorUID>{p}</PredecessorUID>')
                xml.append('        <Type>1</Type>') # Finish-to-Start
                xml.append('      </PredecessorLink>')
        xml.append('    </Task>')
    xml.append('  </Tasks>')
    xml.append('</Project>')
    
    with open(output_path, 'w', encoding='utf-8') as f:
        f.write('\n'.join(xml))
    print(f'Wrote MS Project XML to: {output_path}')

if __name__ == '__main__':
    from calc_schedule import tasks
    generate_msproject_xml(tasks, r'C:\Users\Quân\Downloads\KeHoach_GymBro_Nhom04.xml')
    generate_msproject_xml(tasks, r'c:\xampp\htdocs\QLDAPM26\QLDAPM_Nhom04\KeHoach_GymBro_Nhom04.xml')
