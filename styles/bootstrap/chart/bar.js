$(function () {

	//Bar Chart

	$('#container_bar').highcharts({

	 chart: {

	type: 'column',

	options3d: {

	enabled: true,

	alpha: 10,

	beta: 25,

	depth: 70

	}

	},

	title: {

	text: 'Po Confirmation'

	},

	subtitle: {

	text: ''

	},

	plotOptions: {

	column: {depth: 25

	}

	},

	xAxis: {

	categories: ['Total', 'Today','Below 3','Above 3', 'Above 5', ]

	},

	  yAxis: {

	title: {

	text: null

	 }

	},

	 series: [{

	  name: 'Po Confirmation', data: [263,62,58,15,128]

	 }]

	});

	});

	