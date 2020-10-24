<script type="text/javascript" src="/components/com_jumi/files/kjua/kjua-0.4.0.min.js"></script>  
<script type="text/javascript" src="/components/com_jumi/files/kjua/script.js"></script>  
<link href="/components/com_jumi/files/kjua/style.css" rel="stylesheet" >


<body>

<div id="container">



</div>



</body>



<script>
var el = kjua({
	text: 'hello!', 
	render: 'image',
    // render pixel-perfect lines
    crisp: true,

    // minimum version: 1..40
    minVersion: 1,

    // error correction level: 'L', 'M', 'Q' or 'H'
    ecLevel: 'L',

    // size in pixel
    size: 200,

    // pixel-ratio, null for devicePixelRatio
    ratio: null,

    // code color
    fill: '#333',

    // background color
    back: '#fff',

    // content
    text: 'no text',

    // roundend corners in pc: 0..100
    rounded: 0,

    // quiet zone in modules
    quiet: 0,

    // modes: 'plain', 'label' or 'image'
    mode: 'plain',

    // label/image size and pos in pc: 0..100
    mSize: 30,
    mPosX: 50,
    mPosY: 50,

    // label
    label: 'no label',
    fontname: 'sans',
    fontcolor: '#333',

    // image element
    image: null




});
document.querySelector('container').appendChild(el);
</script>