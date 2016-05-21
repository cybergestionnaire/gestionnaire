


rome(left, {
  dateValidator: rome.val.beforeEq(right), time: false
});

rome(right, {
  dateValidator: rome.val.afterEq(left), time: false
});

