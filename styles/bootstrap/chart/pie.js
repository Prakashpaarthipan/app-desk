$(function () {

	//Bar Chart

	$('#container_pie').highcharts({

	chart: {

	type: 'pie',

	options3d: {

	enabled: true,

	alpha: 45,

	beta: 0

	}

	},

	title: {

	text: 'Goods Ready Entry'

	},

	tooltip: {

	pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'

	},

	plotOptions: {

	pie: {

	allowPointSelect: true,

	cursor: 'pointer',

	depth: 35,

	dataLabels: {

	enabled: true,

	format: '{point.name}'

	}

	}

	},

	series: [{

	type: 'pie',

	name: 'Goods Ready',

	 data: [
				['Total',1062],
				['Today',14],
				['3 Days',218],
                ['ABOVE 3',73],
                ['ABOVE 5', 44],
                
			
				]

	 }]

	});

	});

	