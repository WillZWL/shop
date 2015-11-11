function AddAll(left, right)
{
	var i;
	for (i=0; i<left.options.length; i++) {
		var opt = new Option(left.options[i].text, left.options[i].value);
		right.options[right.options.length] = opt;
	}
	while (left.options.length > 0) {
		left.options[0] = null;
	}
	SortList(right);
}

function AddOne(left, right)
{
	var i;
	if (left.selectedIndex < 0) return;

	for (i=0; i<left.options.length; i++) {
		if (left.options[i].selected) {
			var opt = new Option(left.options[i].text, left.options[i].value);
			right.options[right.options.length] = opt;
		}
	}
	i=0;
	while (i < left.options.length) {
		if (left.options[i].selected) {
			left.options[i] = null;
		} else {
			i++;
		}
	}
	SortList(right);
}

function DelOne(left, right)
{
	var i;
	if (right.selectedIndex < 0) return;

	for (i=0; i<right.options.length; i++) {
		if (right.options[i].selected) {
			var opt = new Option(right.options[i].text, right.options[i].value);
			left.options[left.options.length] = opt;
		}
	}
	i=0;
	while (i < right.options.length) {
		if (right.options[i].selected) {
			right.options[i] = null;
		} else {
			i++;
		}
	}
	SortList(left);
}

function DelAll(left, right)
{
	for (var i=0; i<right.options.length; i++) {
		var opt = new Option(right.options[i].text, right.options[i].value);
		left.options[left.options.length] = opt;
	}
	while (right.options.length > 0) {
		right.options[0] = null;
	}
	SortList(left);
}

function SelectAllItems(lst)
{
	for (var i=0; i<lst.options.length; i++) {
		lst.options[i].selected = true;
	}
}

function SortList(lst)
{
	var t = new Array();
	for (i=0; i<lst.options.length; i++) {
		t[i] = lst.options[i];
	}
	t.sort(CompareOption);
	lst.length = 0;
	for (i=0; i<t.length; i++) {
		lst.options[i] = t[i];
	}
}

function CompareOption(a, b)
{
	if (a.text > b.text) {
		return 1;
	} else if (a.text < b.text) {
		return -1;
	} else {
		return 0;
	}
}

