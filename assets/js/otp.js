// Phone + SMS-code login (see inc/otp.php).
(function () {
  var box = document.querySelector('[data-otp]');
  if (!box) return;
  var q = function (s) { return box.querySelector(s); };
  var fPhone = q('[data-otp-step="phone"]'), fCode = q('[data-otp-step="code"]');
  var iPhone = q('[data-otp-phone]'), iCode = q('[data-otp-code]');
  var err = q('[data-otp-err]'), hint = q('[data-otp-hint]'), resend = q('[data-otp-resend]');
  var phone = '', timer = null;
  var fa = function (s) { return String(s).replace(/\d/g, function (d) { return '۰۱۲۳۴۵۶۷۸۹'[d]; }); };

  function say(m) { err.textContent = m || ''; err.classList.toggle('hidden', !m); }
  function post(action, data) {
    var fd = new FormData(); fd.append('action', action);
    Object.keys(data).forEach(function (k) { fd.append(k, data[k]); });
    return fetch(box.dataset.ajax, { method: 'POST', body: fd, credentials: 'same-origin' }).then(function (r) { return r.json(); });
  }
  function busy(f, on) { var b = f.querySelector('button[type=submit]'); b.disabled = on; b.style.opacity = on ? '.6' : ''; }
  function countdown(n) {
    clearInterval(timer); resend.disabled = true;
    var tick = function () {
      if (n <= 0) { clearInterval(timer); resend.disabled = false; resend.textContent = 'ارسال دوباره'; return; }
      resend.textContent = 'ارسال دوباره (' + fa(n) + ')'; n--;
    };
    tick(); timer = setInterval(tick, 1000);
  }
  function toCode(res) {
    phone = res.data.phone;
    fPhone.classList.add('hidden'); fCode.classList.remove('hidden');
    hint.textContent = 'کد پنج‌رقمی ارسال‌شده به ' + fa(phone) + ' را وارد کنید.';
    iCode.value = ''; iCode.focus(); countdown(res.data.wait || 60);
    if (res.data.debug_code) { say('کد آزمایشی: ' + res.data.debug_code); err.classList.remove('text-hot'); }
  }
  function send(form) {
    say(''); busy(form, true);
    post('kidara_otp_send', { phone: phone || iPhone.value }).then(function (r) {
      busy(form, false);
      if (!r.success) return say(r.data && r.data.msg || 'خطا. دوباره تلاش کنید.');
      toCode(r);
    }).catch(function () { busy(form, false); say('اتصال برقرار نشد.'); });
  }
  fPhone.addEventListener('submit', function (e) { e.preventDefault(); phone = ''; send(fPhone); });
  resend.addEventListener('click', function () { send(fCode); });
  q('[data-otp-edit]').addEventListener('click', function () {
    fCode.classList.add('hidden'); fPhone.classList.remove('hidden'); say(''); phone = '';
    hint.textContent = 'شمارهٔ موبایل را وارد کنید تا کد تأیید برایتان پیامک شود.'; iPhone.focus();
  });
  fCode.addEventListener('submit', function (e) {
    e.preventDefault(); say(''); busy(fCode, true);
    post('kidara_otp_verify', { phone: phone, code: iCode.value, redirect: box.dataset.redirect || '' }).then(function (r) {
      if (r.success) { location.href = r.data.redirect; return; }
      busy(fCode, false); say(r.data && r.data.msg || 'خطا.');
      if (r.data && r.data.expired) { resend.disabled = false; resend.textContent = 'ارسال دوباره'; }
    }).catch(function () { busy(fCode, false); say('اتصال برقرار نشد.'); });
  });
})();
