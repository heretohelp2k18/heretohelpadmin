/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
window.pie = null;
$(document).ready(function(){
    window.sr = ScrollReveal();
    sr.reveal('.reveal',{
        duration: 500,
        reset : true
    });
    
    sr.reveal('.reveal700', { 
        duration: 700,
        scale : 0.8,
        reset : true
    });
    
    sr.reveal('.reveal1000', { 
        duration: 1000,
        scale : 0.8,
        reset : true
    });
    
    sr.reveal('.reveal1100', { 
        duration: 1100,
        scale : 0.8,
        reset : true
    });
    
    sr.reveal('.reveal1200', { 
        duration: 1200,
        scale : 0.8,
        reset : true
    });
    
    sr.reveal('.reveal1300', { 
        duration: 1300,
        scale : 0.8,
        reset : true
    });
    
    $.ajax({
        url : '/welcome/GetCrimeAnalysisByMonth',
        method : 'POST',
        data : {
            month : $("#month_selector").val(),
            year : $("#year_selector").val()
        },
        dataType : "json",
        beforeSend : function(){
        },
        success : function(data){
            if(data.success)
            {
                var valid_pie = false;
                $.each(data.content,function(key,value){
                    var int_value = parseInt(value['value'])
                    data.content[key]['value'] = int_value;
                    if(int_value > 0)
                    {
                        valid_pie = true;
                    }
                });
                
                if(valid_pie)
                {
                    pie = new d3pie("myPie", {
                            header: {
                            },
                            data: {
                                    content: data.content
                            }
                    });
                }
                else
                {
                   $(".no-data-found").css("display","block");
                }
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }
        },
        error : function(){
            swal("Error", "Error connecting to server.", "error");
        }
    });
    
    //sorting
    $("#sort_selector").change(function(){
        var sorter = $(this).val();
        if(sorter == 1)
        {
            $(".day-holder").show();
            $(".week-holder").hide();
            $(".month-holder").hide();
            $(".year-holder").hide();
        }
        else if(sorter == 2)
        {
            $(".week-holder").show();
            $(".day-holder").hide();
            $(".month-holder").hide();
            $(".year-holder").hide();
        }
        else if(sorter == 3)
        {
            $(".day-holder").hide();
            $(".week-holder").hide();
            $(".month-holder").show();
            $(".year-holder").show();
        }
        else if(sorter == 4)
        {
            $(".day-holder").hide();
            $(".week-holder").hide();
            $(".month-holder").hide();
            $(".year-holder").show();
        }
    });
    
    $("#show_results").click(function(){
        var sorter = $("#sort_selector").val();
        if(sorter == 1)
        {
            //day
            RenderPieByDay();
        }
        else if(sorter == 2)
        {
            //week
            RenderPieByWeek();
        }
        else if(sorter == 3)
        {
            //month
            RenderPieByMonth();
        }
        else if(sorter == 4)
        {
            //year
            RenderPieByYear();
        }
    });
    
    $(".owl-carousel").owlCarousel({
                    items: 4,
                    stagePadding: 50
                });
});

$(function() {
$.scrollify({
                section : ".main-section",
        });
});
$.scrollify({
    section : "main-section",
    sectionName : "section-name",
    interstitialSection : "",
    easing: "easeOutExpo",
    scrollSpeed: 1100,
    offset : 0,
    scrollbars: true,
    standardScrollElements: "",
    setHeights: true,
    overflowScroll: true,
    updateHash: true,
    touchScroll:true,
    before:function() {},
    after:function() {},
    afterResize:function() {},
    afterRender:function() {}
});

var RenderPieByMonth = function()
{
    $.ajax({
        url : '/welcome/GetCrimeAnalysisByMonth',
        method : 'POST',
        data : {
            month : $("#month_selector").val(),
            year : $("#year_selector").val()
        },
        dataType : "json",
        beforeSend : function(){
        },
        success : function(data){
            if(data.success)
            {
                var valid_pie = false;
                $.each(data.content,function(key,value){
                    var int_value = parseInt(value['value']);
                    data.content[key]['value'] = int_value;
                    if(int_value > 0)
                    {
                        valid_pie = true;
                    }
                });
                
                if(valid_pie)
                {
                    $(".no-data-found").css("display","none");
                    $("#myPie").css("display","block");
                    if(pie == null)
                    {
                        window.pie = new d3pie("myPie", {
                                header: {
                                },
                                data: {
                                        content: data.content
                                }
                        });
                    }
                    else
                    {
                        pie.updateProp("data.content", data.content);
                    }
                }
                else
                {
                   $(".no-data-found").css("display","block");
                   $("#myPie").css("display","none");
                }
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }
        },
        error : function(){
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var RenderPieByYear = function()
{
    $.ajax({
        url : '/welcome/GetCrimeAnalysisByYear',
        method : 'POST',
        data : {
            year : $("#year_selector").val()
        },
        dataType : "json",
        beforeSend : function(){
        },
        success : function(data){
            if(data.success)
            {
                var valid_pie = false;
                $.each(data.content,function(key,value){
                    var int_value = parseInt(value['value']);
                    data.content[key]['value'] = int_value;
                    if(int_value > 0)
                    {
                        valid_pie = true;
                    }
                });
                
                if(valid_pie)
                {
                    $(".no-data-found").css("display","none");
                    $("#myPie").css("display","block");
                    if(pie == null)
                    {
                        window.pie = new d3pie("myPie", {
                                header: {
                                },
                                data: {
                                        content: data.content
                                }
                        });
                    }
                    else
                    {
                        pie.updateProp("data.content", data.content);
                    }
                }
                else
                {
                   $(".no-data-found").css("display","block");
                   $("#myPie").css("display","none");
                }
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }
        },
        error : function(){
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var RenderPieByWeek = function()
{
    $.ajax({
        url : '/welcome/GetCrimeAnalysisByWeek',
        method : 'POST',
        data : {
            week : $("#week_selector").val()
        },
        dataType : "json",
        beforeSend : function(){
        },
        success : function(data){
            if(data.success)
            {
                var valid_pie = false;
                $.each(data.content,function(key,value){
                    var int_value = parseInt(value['value']);
                    data.content[key]['value'] = int_value;
                    if(int_value > 0)
                    {
                        valid_pie = true;
                    }
                });
                
                if(valid_pie)
                {
                    $(".no-data-found").css("display","none");
                    $("#myPie").css("display","block");
                    if(pie == null)
                    {
                        window.pie = new d3pie("myPie", {
                                header: {
                                },
                                data: {
                                        content: data.content
                                }
                        });
                    }
                    else
                    {
                        pie.updateProp("data.content", data.content);
                    }
                }
                else
                {
                   $(".no-data-found").css("display","block");
                   $("#myPie").css("display","none");
                }
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }
        },
        error : function(){
            swal("Error", "Error connecting to server.", "error");
        }
    });
};

var RenderPieByDay = function()
{
    $.ajax({
        url : '/welcome/GetCrimeAnalysisByDay',
        method : 'POST',
        data : {
            day : $("#day_selector").val()
        },
        dataType : "json",
        beforeSend : function(){
        },
        success : function(data){
            if(data.success)
            {
                var valid_pie = false;
                $.each(data.content,function(key,value){
                    var int_value = parseInt(value['value'])
                    data.content[key]['value'] = int_value;
                    if(int_value > 0)
                    {
                        valid_pie = true;
                    }
                });
                
                if(valid_pie)
                {
                    $(".no-data-found").css("display","none");
                    $("#myPie").css("display","block");
                    if(pie == null)
                    {
                        window.pie = new d3pie("myPie", {
                                header: {
                                },
                                data: {
                                        content: data.content
                                }
                        });
                    }
                    else
                    {
                        pie.updateProp("data.content", data.content);
                    }
                }
                else
                {
                   $(".no-data-found").css("display","block");
                   $("#myPie").css("display","none");
                }
            }
            else
            {
                swal("Error", "Error connecting to server.", "error");
            }
        },
        error : function(){
            swal("Error", "Error connecting to server.", "error");
        }
    });
};