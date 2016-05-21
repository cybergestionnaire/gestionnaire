var moment = rome.moment;


rome(dt1);
rome(dt2);
rome(dt3);
rome(dt4);
rome(dt5);
rome(dt6);
rome(dt7);
rome(dt8);
rome(dt9);
rome(dt10);
rome(dt11);
rome(dt12);
rome(dt13);
rome(dt14);
rome(dt15);
rome(dt16);
rome(dt17);
rome(dt18);
rome(dt19);
rome(dt20);


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

