var days = ["Sun", "Mon", "Tues", "Wed", "Thu", "Fri", "Sat"];
function commonOptions(chart_type = "line", top_title, tooltip_title,data,colors=[],show_shadow=false) {
    return {
        series: [
            {
                name: tooltip_title,
                data,
            },
        ],
        stroke: { width: 1 },
        chart: {
            height: 350,
            type: chart_type,
            zoom: {
                enabled: false,
            },
            toolbar: {
                show: false,
            },
            dropShadow: {
                enabled: show_shadow,
                top: 18,
                left: 2,
                blur: 3,
                color: config.colors.info,
                opacity: 0.15,
            },
        },

        dataLabels: {
            enabled: true,
        },
        colors: colors.length>0?colors:[config.colors.info],
        labels: {
            show: !0,
            style: {
                fontSize: "13px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
            },
        },
        title: {
            text: top_title,
            style: {
                fontSize: "16px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
                fontWeight: "600",
            },
        },
    };
}


window.onload = function () {
    let callbackSuccess = function (res) {
        if (res["success"]) {
            let er = JSON.parse(res["message"]);
            console.log(er);
            let expense = er["expense"];

            let sell = er["sell"];
            let dates = er["dates"];
           

            let order_monthwise_val = er["order_monthwise_val"];
            let order_daily_val = er["order_daily_val"];
            let order_daily_dates = er["order_daily_dates"];
            let order_weekly_val = er["order_weekly_val"];

            let paid_order_monthwise_val = er["paid_order_monthwise_val"];
            let paid_order_daily_val = er["paid_order_daily_val"];
            let paid_order_daily_dates = er["paid_order_daily_dates"];
            let paid_order_weekly_val = er["paid_order_weekly_val"];
            /***leads vals */
            let leads_monthwise_val = er["leads_monthwise_val"];
            let leads_daily_val = er["leads_daily_val"];
            let leads_daily_dates = er["leads_daily_dates"];
            let leads_weekly_val = er["leads_weekly_val"];

            let suc_leads_monthwise_val = er["successfull_leads_monthwise_val"];
            let suc_leads_daily_val = er["successfull_leads_daily_val"];
            let suc_leads_daily_dates = er["successfull_leads_daily_dates"];
            let suc_leads_weekly_val = er["successfull_leads_weekly_val"];

            /**sell */
             let sell_monthwise_val = er["sell_monthwise_val"];
             let sell_daily_val = er["sell_daily_val"];
             let sell_daily_dates = er["sell_daily_dates"];
             let sell_weekly_val = er["sell_weekly_val"];
            /**Expense */
             let exp_monthwise_val = er["exp_monthwise_val"];
             let exp_daily_val = er["exp_daily_val"];
             let exp_daily_dates = er["exp_daily_dates"];
             let exp_weekly_val = er["exp_weekly_val"];

            drawSellExpendChart(expense, sell, dates);
          //  drawSellMonthlyChart(sell_monthwise_val);
          //  drawExpenseMonthlyChart(expense_monthwise_val);

            drawOrderMonthlyChart(order_monthwise_val);
            drawOrderDailyChart(order_daily_val, order_daily_dates);
            drawOrderWeeklyChart(order_weekly_val);

            drawPaidOrderMonthlyChart(paid_order_monthwise_val);
            drawPaidOrderDailyChart(
                paid_order_daily_val,
                paid_order_daily_dates
            );
            drawPaidOrderWeeklyChart(paid_order_weekly_val);
            /***Leads */
            drawLeadsMonthlyChart(leads_monthwise_val);
            drawLeadsDailyChart(leads_daily_val, leads_daily_dates);
            drawLeadsWeeklyChart(leads_weekly_val);

            drawSucLeadsMonthlyChart(suc_leads_monthwise_val);
            drawSucLeadsDailyChart(suc_leads_daily_val, suc_leads_daily_dates);
            drawSucLeadsWeeklyChart(suc_leads_weekly_val);
            /**Sell */

             drawSellMonthlyChart(sell_monthwise_val);
             drawSellDailyChart(sell_daily_val, sell_daily_dates);
             drawSellWeeklyChart(sell_weekly_val);
            /**Exp */

             drawExpMonthlyChart(exp_monthwise_val);
             drawExpDailyChart(exp_daily_val, exp_daily_dates);
             drawExpWeeklyChart(exp_weekly_val);
        }
    };

    objectAjaxNoLoaderNoAlert(
        {},
        "/admin/dashboard_data",
        callbackSuccess,
        undefined,
        "GET",
        false
    );
};
function drawSellExpendChart(expense, sell, dates) {
    var options = {
        series: [
            {
                name: "Expense",
                data: expense,
            },
            {
                name: "Sell",
                data: sell,
            },
        ],
        chart: {
            height: 350,
            type: "area",
            toolbar: {
                show: 0,
            },
        },
        colors: [config.colors.info, config.colors.danger],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: "smooth",
            width: 1,
        },
        xaxis: {
            type: "date",
            categories: dates,
        },
        tooltip: {
            x: {
                format: "dd/MM/yy",
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart1"), options);
    chart.render();
}
function drawSellMonthlyChart(sell_monthly) {
    let options = commonOptions(
        chart_type = "area",
        'Monthly Sell',
        'Sell Amount',
        sell_monthly,
        [config.colors.warning]
    );

    options["xaxis"] = {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
        ],
        labels: {
            show: !0,
            style: {
                fontSize: "13px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
            },
        },
    };
    
    var chart = new ApexCharts(document.querySelector("#chart2"), options);
    chart.render();
}
function drawExpenseMonthlyChart(expense_monthly) {
     let options = commonOptions(
         (chart_type = "area"),
         "Monthly Expenditure",
         "Spent Amount",
         expense_monthly,
         
     );

    options["xaxis"] = {
        categories: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
        ],
        labels: {
            show: !0,
            style: {
                fontSize: "13px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart3"), options);
    chart.render();
}
/**Order***/
function drawOrderMonthlyChart(vals) {
 let options = commonOptions(
         (chart_type = "line"),
         "Monthly Order Count",
         "Order Count ",
         vals,
         
     );
        options['xaxis']={
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
            ],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
            },
        }
        options['yaxis']={
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Order Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#order_monthly_chart"),
        options
    );
    chart.render();
}
function drawOrderDailyChart(vals, dates) {
            let options=commonOptions('line','Daily  Order Count','Order Count',vals)

         options['xaxis']= {
            type: "date",
            categories: dates,
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    console.log("ok");
                    if (val !== undefined) return days[new Date(val).getDay()];
                    // return val;
                },
            },
        }
    options['yaxis'] = {
        axisBorder: {
            show: true,
            color: "silver",
            offsetX: 0,
            offsetY: 0,
        },
        title: {
            text: "Order Count",
            style: {
                fontSize: "13px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
                fontWeight: "500",
            },
        },
        labels: {
            show: !0,
            style: {
                fontSize: "13px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
            },
            formatter: function (val) {
                return parseInt(val);
            },
        },
    };
    

    var chart = new ApexCharts(
        document.querySelector("#order_daily_chart"),
        options
    );
    chart.render();
}
function drawOrderWeeklyChart(vals) {
     let options=commonOptions('line','Weekly  Order Count','Order Count',vals)

            
    options['xaxis'] = {
        categories: [1, 2, 3, 4],
        labels: {
            show: !0,
            style: {
                fontSize: "13px",
                fontFamily: "IBM Plex Sans",
                colors: "grey",
            },
            formatter: (value) => {
                let g = "st";
                if (value == 2) g = "nd";
                else if (value == 3) g = "rd";
                else if (value == 4) g = "th";
                else {
                }

                return value + " " + g + " Week";
            },
        },
    };

         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Order Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#order_weekly_chart"),
        options
    );
    chart.render();
}
/*Paid order **/
function drawPaidOrderMonthlyChart(vals) {
    let options=commonOptions('line','Monthly Paid Order Count','Paid Order Count',vals)

         options['xaxis']= {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
            ],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Order Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#paid_order_monthly_chart"),
        options
    );
    chart.render();
}
function drawPaidOrderDailyChart(vals, dates) {
   
 let options=commonOptions('line','Daily Paid Order Count','Paid Order Count',vals)
        options['xaxis']={
            type: "date",
            categories: dates,
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    console.log("ok");
                    if (val !== undefined) return days[new Date(val).getDay()];
                    // return val;
                },
            },
        }
        options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Order Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#paid_order_daily_chart"),
        options
    );
    chart.render();
}
function drawPaidOrderWeeklyChart(vals) {
      let options=commonOptions('line','Weekly Paid Order Count','Paid Order Count',vals)

              options['xaxis']= {
            categories: [1, 2, 3, 4],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: (value) => {
                    let g = "st";
                    if (value == 2) g = "nd";
                    else if (value == 3) g = "rd";
                    else if (value == 4) g = "th";
                    else {
                    }

                    return value + " " + g + " Week";
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Order Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#paid_order_weekly_chart"),
        options
    );
    chart.render();
}
/**Leads***/
function drawLeadsMonthlyChart(vals) {
     let options=commonOptions('line','Monthly Leads Count','Leads Count',vals)

         options['xaxis']= {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
            ],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Leads Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#leads_monthly_chart"),
        options
    );
    chart.render();
}
function drawLeadsDailyChart(vals, dates) {
       let options=commonOptions('line','Daily Leads Count','Leads Count',vals)

         options['xaxis']= {
            type: "date",
            categories: dates,
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    console.log("ok");
                    if (val !== undefined) return days[new Date(val).getDay()];
                    // return val;
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Leads Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#leads_daily_chart"),
        options
    );
    chart.render();
}
function drawLeadsWeeklyChart(vals) {
   let options=commonOptions('line','Weekly Leads Count','Leads Count',vals)
       
         options['xaxis']= {
            categories: [1, 2, 3, 4],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: (value) => {
                    let g = "st";
                    if (value == 2) g = "nd";
                    else if (value == 3) g = "rd";
                    else if (value == 4) g = "th";
                    else {
                    }

                    return value + " " + g + " Week";
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Leads Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#leads_weekly_chart"),
        options
    );
    chart.render();
}
/*Paid order **/
function drawSucLeadsMonthlyChart(vals) {
      let options=commonOptions('line','Monthly Converted Leads Count','Leads Count',vals)

         options['xaxis']= {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
            ],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Converted Leads Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#suc_leads_monthly_chart"),
        options
    );
    chart.render();
}
function drawSucLeadsDailyChart(vals, dates) {
      let options=commonOptions('line','Daily Converted Leads Count','Leads Count',vals)

         options['xaxis']= {
            type: "date",
            categories: dates,
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    if (val !== undefined) return days[new Date(val).getDay()];
                    // return val;
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Converted Leads Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#suc_leads_daily_chart"),
        options
    );
    chart.render();
}
function drawSucLeadsWeeklyChart(vals) {
   let options=commonOptions('line','Weekly Converted Leads Count','Leads Count',vals)
         options['xaxis']= {
            categories: [1, 2, 3, 4],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: (value) => {
                    let g = "st";
                    if (value == 2) g = "nd";
                    else if (value == 3) g = "rd";
                    else if (value == 4) g = "th";
                    else {
                    }

                    return value + " " + g + " Week";
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Converted Leads Count",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#suc_leads_weekly_chart"),
        options
    );
    chart.render();
}

/*Sell **/
function drawSellMonthlyChart(vals) {
      let options=commonOptions('line','Monthly Sales','Sale Amount',vals)

         options['xaxis']= {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
            ],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Sale Amount",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#sell_monthly_chart"),
        options
    );
    chart.render();
}
function drawSellDailyChart(vals, dates) {
      let options=commonOptions('line','Daily Sale','Sale Amount',vals)

         options['xaxis']= {
            type: "date",
            categories: dates,
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    if (val !== undefined) return days[new Date(val).getDay()];
                    // return val;
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Sell  Amount",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#sell_daily_chart"),
        options
    );
    chart.render();
}
function drawSellWeeklyChart(vals) {
   let options=commonOptions('line','Weekly Sales','Sale Amount',vals)
         options['xaxis']= {
            categories: [1, 2, 3, 4],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: (value) => {
                    let g = "st";
                    if (value == 2) g = "nd";
                    else if (value == 3) g = "rd";
                    else if (value == 4) g = "th";
                    else {
                    }

                    return value + " " + g + " Week";
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Sell Amount",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#sell_weekly_chart"),
        options
    );
    chart.render();
}

/*Expenditure **/
function drawExpMonthlyChart(vals) {
      let options=commonOptions('line','Monthly Expenditure','Spent Amount',vals)

         options['xaxis']= {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
            ],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Expense Amount",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#exp_monthly_chart"),
        options
    );
    chart.render();
}
function drawExpDailyChart(vals, dates) {
      let options=commonOptions('line','Daily Expense','Spent Amount',vals)

         options['xaxis']= {
            type: "date",
            categories: dates,
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    if (val !== undefined) return days[new Date(val).getDay()];
                    // return val;
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Spent  Amount",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#exp_daily_chart"),
        options
    );
    chart.render();
}
function drawExpWeeklyChart(vals) {
   let options=commonOptions('line','Weekly Expense','Spent Amount',vals)
         options['xaxis']= {
            categories: [1, 2, 3, 4],
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: (value) => {
                    let g = "st";
                    if (value == 2) g = "nd";
                    else if (value == 3) g = "rd";
                    else if (value == 4) g = "th";
                    else {
                    }

                    return value + " " + g + " Week";
                },
            },
        }
         options['yaxis']= {
            axisBorder: {
                show: true,
                color: "silver",
                offsetX: 0,
                offsetY: 0,
            },
            title: {
                text: "Expenditure Amount",
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                    fontWeight: "500",
                },
            },
            labels: {
                show: !0,
                style: {
                    fontSize: "13px",
                    fontFamily: "IBM Plex Sans",
                    colors: "grey",
                },
                formatter: function (val) {
                    return parseInt(val);
                },
            },
        }
    

    var chart = new ApexCharts(
        document.querySelector("#exp_weekly_chart"),
        options
    );
    chart.render();
}