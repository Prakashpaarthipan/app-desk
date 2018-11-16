$(function () {

	//Bar Chart

	$('#container_don').highcharts({

	chart: {

	type: 'pie',

	options3d: {

	enabled: true,

	alpha: 45

	}

	},

	

	title: {

	text: 'Lr Entry'

	},

	subtitle: {

	text: ''

	},

	plotOptions: {

	pie: {

	innerSize: 100,

	depth: 45

	}

	},

	series: [{

	name: 'Lr Entry',

	data: [

	['TOTAL',3014],
			['Due Date Close',652],
			['ABOVE 7 Days',894],
			['ABOVE 15 Days',237],
			['ABOVE 30 Days',14],

	]

	}]

	});

	});

	