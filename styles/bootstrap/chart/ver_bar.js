var charts = [],

	//Bar Chart

	containers = $('#container_bub'),

	 datasets = [{

	name: 'Despatch Confirmation',

	data: [1329,12,238,65,138]

	},

	 ];

	$.each(datasets, function(i, dataset) {

	charts.push(new Highcharts.Chart({

	

	chart: {

	renderTo: containers[i],

	type: 'bar',

	marginLeft: i === 0 ? 100 : 10

	},

	title: {

	text: dataset.name,

	align: 'left',

	x: i === 0 ? 90 : 0

	},

	credits: {

	enabled: false

	},

	xAxis: {

	categories: ['TOTAL','TODAY','BELOW 3', 'ABOVE 3', 'ABOVE 7'],

	labels: {

	enabled: i === 0

	}

	},

	yAxis: {

	 allowDecimals: false,

	title: {

	text: null

	},

	},

	legend: {

	enabled: false

	},

	series: [dataset]

	}));

	});

	