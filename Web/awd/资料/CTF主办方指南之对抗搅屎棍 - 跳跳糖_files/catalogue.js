const articleData = $('article')[0].innerHTML.match(/<[hH][1-6].*>.*?<\/[hH][1-6]>/g);

//console.log(articleData)
$('#catalogue').html(articleData);
// const toc = data.content.match(/<[hH][1-6].*>.*?<\/[hH][1-6]>/g)