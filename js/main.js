require.config({
    paths : {
        'jquery' : '//cdn.bootcss.com/jquery/2.1.4/jquery.min',
        'app' : 'app',
    },
    urlArgs: "bust=" +  (new Date()).getTime(),
});

require(['jquery','app'],function($,app){
    $('.imageInfo').on('click',function(){
        console.log(app.W);
        console.log(app.H);
    });

});
