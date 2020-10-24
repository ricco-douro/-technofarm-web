/**
 * Dark blue theme for Highcharts JS
 * @author Torstein Honsi
 */
 
Highcharts.theme = {
    colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
	credits:
	{
		enabled: false
	},
	chart: 
	{
		backgroundColor:null,
		width: null,
		height:null,
        spacingBottom: 0,
        spacingTop: 10,
        spacingLeft: 0,
        spacingRight: 0,			
//		backgroundColor: {
	//		linearGradient: [0, 0, 250, 500],
		//	stops: [
			//	[0, 'rgb(38, 34, 82)'],
				//[1, 'rgb(38, 35, 86)']
//			]
//		},
//		borderColor:  'rgb(53, 50, 102)',
//		borderWidth: 1,
//		className: '',
//		plotBackgroundColor: 'rgb(53, 50, 102)',
		plotBorderColor: '#efefef',
		plotBorderWidth: 1
	},
	
	title: 
	{
		align: "center",
		floating: false,
		margin: 0,
		style: 
			{
			color: '#333333', // cor do titulo do gráfico
			fontSize: '150%',//texto das colunas
			fontFamily: 'NexaLight-Regular',
			},
		useHTML: false,
		verticalAlign:"",

		x: 0,
		y: 10,
	},
	
	subtitle: 
	{
		align: "center",
		floating: false,
		margin: 0,
		style: 
			{
			color: '#333333',// cor do subtitulo
			fontSize: '150%',//texto das colunas
			fontFamily: 'NexaLight-Regular',			
			},
		useHTML: false,
		verticalAlign:"",
		x: 0,
		y: 45,	
	},
	
	series: 
	{ 
		colorByPoint: true,
		fontSize: '16px',//texto das colunas			
		fontFamily: 'NexaLight-Regular',	
							
	},
			

	xAxis: 
	{
		gridLineColor: '#333333',//Linhas verticais	
		gridLineWidth: 0,
		labels: 
		{
			style: 
			{
				color: '#333333', //texto das colunas 
				fontSize: '13px',//texto das colunas
				fontFamily: 'NexaLight-Regular',				
			}
		},
		lineColor: null,
		tickColor: '#ffffff',//Linhas verticais	
		title: 
		{
			style: 
			{
				color: '#74797d',// nao usando  
				fontSize: '13px',//texto das colunas
				fontFamily: 'NexaLight-Regular',								
			}
		}
	},
	
	yAxis:
	{
		gridLineColor: '#333333',//Linhas horizontais	
		gridLineWidth: 0,
		labels: 
		{
			style: 
			{
				color:'#74797d', //texto das linhas
				fontSize: '10px',//texto das linhas
				fontFamily: 'NexaLight-Regular',				
			}
		},
		lineColor: null,
		minorTickInterval: null,
		tickColor:'#333333',//Linhas horizontais
		tickWidth: 1,
		title: 
		{
			style: 
			{
				color: '#74797d',// nao usando  
				fontFamily: 'NexaLight-Regular',								
			}
		}
	},
	
	tooltip: 
	{
		backgroundColor:'#ffffff',//mouse hover na barra
		crosshairs: true,
		borderWidth:2,
		followTouchMove: true,
		style: 
		{
			width: '250%',			
			color: '#000000',
			fontSize:'14px',//texto das linhas														
			fontFamily: 'NexaLight-Regular',			
			cursor: 'default', 
			padding: 15,
			pointerEvents: "none", 
			whiteSpace: "nowrap"
			
		}
	},
	
	toolbar: {
		itemStyle: {
			color: '#74797d',// nao usando 
			fontFamily: 'NexaLight-Regular',				
		}
	},
	
	plotOptions: {

	
	solidgauge:
		{
		dataLabels: 
			{
				borderWidth: 0,				
				useHTML: false
			},
		animation: 
			{
				duration: 4000				
			}					
		},
		
		line: {
			dataLabels: {
				color: '#74797d',// nao usando 
				fontFamily: 'NexaLight-Regular',				
			},
		animation: 
			{
				duration: 1000				
			},			
			marker: {
				lineColor: '#74797d'// nao usando 
			}
		},
		
		treemap:{
			
			},
			
		spline: {
			marker: {
				lineColor: '#74797d'// nao usando
			},
		animation: 
			{
				duration: 4000				
			},			
		},
		
		scatter: {		
			marker: {
				lineColor: '#74797d'// nao usando
			},
		animation: 
			{
				duration: 4000				
			},			
		},
		
		candlestick: {
			lineColor: '#74797d'// nao usando
		},
		animation: 
			{
				duration: 4000				
			},		
		
		pie:
		{
			dataLabels:
			{
				style:
					{
					fontSize: "13px",
					fontFamily: 'NexaLight-Regular',				
					}
			},
		animation: 
			{
				duration: 4000				
			},			
		
		}		
	},
	legend: {
		itemStyle: {
			color:'#74797d',//mouse hover legendas
			fontSize: '13px',//texto das linhas
			fontFamily: 'NexaLight-Regular',				
								
		},
		itemHoverStyle: {
			color: '#74797d',//mouse hover legendas
			fontFamily: 'NexaLight-Regular',							
		},
		itemHiddenStyle: {
			color: '#74797d',//mouse hover legendas
			fontFamily: 'NexaLight-Regular',			
		}
	},
	credits: {
		style: {
			fontFamily: 'NexaLight-Regular',							
			color: '#74797d'// nao usando
		}
	},
	labels: {
		style: {
			fontFamily: 'NexaLight-Regular',							
			color: '#74797d'// nao usando
		}
	},


	navigation: {
		buttonOptions: {
			symbolStroke: '#74797d',// nao usando
			hoverSymbolStroke: '#74797d',// nao usando
			theme: {
				fill: {
					linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
					stops: [
						[0.4, '#74797d'],// nao usando
						[0.6, '#74797d']// nao usando
					]
				},
				stroke: '#74797d'// nao usando
			}
		}
	},

	// scroll charts
	rangeSelector: {
		buttonTheme: {
			fill: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0.4, '#000000'],// nao usando
					[0.6, '#000000']// nao usando
				]
			},
			stroke: '#000000',// nao usando
			style: {
				fontFamily: 'NexaLight-Regular',								
				color: '#000000',// nao usando
			},
			states: {
				hover: {
					fill: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						stops: [
							[0.4, '#000000'],// nao usando
							[0.6, '#000000']// nao usando
						]
					},
					stroke: '#000000',// nao usando
					style: {
						fontFamily: 'NexaLight-Regular',										
						color: '#000000'// nao usando
					}
				},
				select: {
					fill: {
						linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
						stops: [
							[0.1, '#000000'],// nao usando
							[0.3, '#000000']// nao usando
						]
					},
					stroke: '#000000',// nao usando
					style: {
						fontFamily: 'NexaLight-Regular',										
						color: '#000000'// nao usando
					}
				}
			}
		},
		inputStyle: {
			backgroundColor: '#000000',// nao usando
			fontFamily: 'NexaLight-Regular',							
			color: '#000000'// nao usando
		},
		labelStyle: {
			fontFamily: 'NexaLight-Regular',							
			color: '#000000'// nao usando
		}
	},

	navigator: {
		handles: {
			backgroundColor: '#000000',// nao usando
			borderColor: '#000000'// nao usando
		},
		outlineColor: '#000000',// nao usando
		maskFill: '#000000',// nao usando
		series: {
			color: '#000000',// nao usando
			lineColor: '#000000'// nao usando		
		}
	},

	scrollbar: {
		barBackgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0.4, '#000000'],// nao usando
					[0.6, '#000000']// nao usando
				]
			},
		barBorderColor: '#000000',// nao usando
		buttonArrowColor: '#000000',// nao usando
		buttonBackgroundColor: {
				linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
				stops: [
					[0.4, '#000000'],// nao usando
					[0.6, '#000000']// nao usando
				]
			},
		buttonBorderColor: '#000000',// nao usando
		rifleColor: '#000000',// nao usando
		trackBackgroundColor: {
			linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
			stops: [
				[0, '#000000'],// nao usando
				[1, '#000000']// nao usando
			]
		},
		trackBorderColor: '#000000'// nao usando
	},

	// special colors for some of the
	legendBackgroundColor: '#000000',// nao usando
	background2: '#000000',// nao usando
	dataLabelsColor: '#000000',// nao usando
	textColor: '#000000',// nao usando
	maskColor: '#000000'// nao usando
};


// Apply the theme
	var highchartsOptions = Highcharts.setOptions(Highcharts.theme);    