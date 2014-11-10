$(function () {

	if ($('body').is('.animated')) {
		var $messages = $('.msg').hide();
		var $fields   = $('p', '.field').hide();

		$('a', '#page > header').click(function(e) {
			$('.field:first-child').toggleClass('success');
			$('.field:not(:first-child)').toggleClass('error');

			$fields.slideToggle();
			$messages.slideToggle();
			e.preventDefault();
		});
	}

	$('.demo').each(function() {
		var $this   = $(this);
		var $parent = $this.parent();
		var width   = $parent.width();

		if ($.fn.tipsy) $this.attr('title', width + 'px').tipsy({
			opacity: 1,
			gravity: 's',
		});
	});

	var visits = [], clicks = [];

	for (var i = 0; i <= 20; i++) {
		visits.push([i * 5, 50 + Math.floor((Math.random() < 0.5 ? -1 : 1) * Math.random() * 30)]);
		clicks.push([i * 5, 50 + Math.floor((Math.random() < 0.5 ? -1 : 1) * Math.random() * 15)]);
	}

	var plot = $.plot($('#line'), [{ data: visits, label: 'Visits', color: '#8d929e'}, { data: clicks, label: 'Clicks', color: '#40444e' }], {
		series: {
			lines: { show: true },
			points: { show: true }
		},
		grid: { hoverable: true, clickable: true },
		yaxis: { min: 0, max: 100 },
		xaxis: { min: 0, max: 100 },
	});

	var data = [], totalPoints = 200;

	function getRandomData() {
		if (data.length > 0)
			data = data.slice(1);

		while (data.length < totalPoints) {
			var prev = data.length > 0 ? data[data.length - 1] : 50;
			var y = prev + Math.random() * 10 - 5;

			if (y < 0) y = 0;
			if (y > 100) y = 100;

			data.push(y);
		}

		var res = [];

		for (var i = 0; i < data.length; ++i)
			res.push([i, data[i]])
			return res;
	}

	var updateInterval = 200;

	var options = {
		yaxis: { min: 0, max: 100 },
		xaxis: { min: 0, max: 100 },
		colors: ['#8d929e'],
		series: {
			lines: { 
				fill: true,
				fillColor: { colors: [{ opacity: 0.6 }, { opacity: 0.2 } ]},
				steps: false,
			}
		}
	};

	var plot = $.plot($('#live'), [ getRandomData() ], options);

	function update() {
		plot.setData([ getRandomData() ]);
		plot.draw();

		setTimeout(update, updateInterval);
	}

	update();

	var dataPie = [], series = Math.floor(Math.random() * 10);

	for(var i = 0; i < series; i++) {
		dataPie[i] = { label: 'Series' + (i + 1), data: Math.floor(Math.random() * 100) + 1 }
	}
	
	$.plot($('#pie'), dataPie, {
		series: {
			pie: { 
				show: true,
				radius: 1,
				label: {
					show: true,
					radius: 2/3,
					formatter: function(label, series){
						return '<div style="text-align:center;color:white;">' + label + '<br>' + Math.round(series.percent) + '%</div>';
					},
					threshold: 0.1,
				}
			},
		},
		legend: {
			show: false
		},
	});

	$.plot($('#square'), dataPie, {
		series: {
			pie: { 
				show: true,
				radius: 300,
				label: {
					show: true,
					formatter: function(label, series) {
						return '<div style="text-align:center;color:white;">' + label + '<br>' + Math.round(series.percent) + '%</div>';
					},
					threshold: 0.1
				}
			}
		},
		legend: {
			show: false
		}
	});

	var bars = [];

	for (var i = 0; i <= 50; i++) {
		bars.push([i * 2, Math.floor(Math.random() * 50)]);
	}

	$.plot($('#bars'), [{ data: bars }], {
		series: {
			bars: { 
				show: true,
				lineWidth: 0.5,
				align: "left", 
				horizontal: false,
			},
		},
		grid: { hoverable: true, clickable: true },
		yaxis: { min: 0, max: 50 },
		xaxis: { min: 0, max: 50 },
	});
	

});