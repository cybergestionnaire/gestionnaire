var moment = rome.moment;


rome(dt0, {time: false});
rome(dt1, {date: false});

var picker = rome(ind);

if (toggle.addEventListener) {
  toggle.addEventListener('click', toggler);
} else if (toggle.attachEvent) {
  toggle.attachEvent('onclick', toggler);
} else {
  toggle.onclick = toggler;
}

function toggler () {
  if (picker.destroyed) {
    picker.restore();
  } else {
    picker.destroy();
  }
  toggle.innerHTML = picker.destroyed ? 'Restore <code>rome</code> instance!' : 'Destroy <code>rome</code> instance!';
}
