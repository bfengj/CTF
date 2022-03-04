document.getElementById('form').addEventListener('submit', e => {
	e.preventDefault();

	fetch('/api/submit', {
		method: 'POST',
		body: JSON.stringify({
			'hero.name': document.querySelector('textarea[type=text]').value
		}),
		headers: {'Content-Type': 'application/json'}
	}).then(resp => {
		return resp.json();
	}).then(data => {
		document.getElementById('output').innerHTML = data.response;
	});

});