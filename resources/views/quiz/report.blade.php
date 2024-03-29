<?php

$attempts = \App\Attempt::where('quiz_id', $id)->get();

$answers = \DB::table('answers')->join('questions', 'questions.id', '=', 'answers.question_id')->select('*')->where('questions.quiz_id', $id)->where('answers.correct', 1)->count();

$ranks = [];

$users = \App\User::where('role_id', 2)->get();


foreach ($users as $u) {
    $rank = \DB::table('answers')->join('attempts', 'attempts.aid', '=', 'answers.id')
        ->select('*')
        ->where('attempts.quiz_id', $id)->where('answers.correct', 1)
        ->where('attempts.user_id', $u->id)
        ->count();
    $ranks[$u->id] = $rank;
}


arsort($ranks);




?>

<?php

//$qf = \App\Quizfeedback::where('quiz_id', $id)->where('published',0)->count();

$qf = \App\Quiz::where('id', $id)->where('quiz_status', 'EXECUTION_DONE')->count();

$qf1 = \App\Quiz::where('id', $id)->where('quiz_status', 'EXECUTION_STARTED')->count();

$qf2 = \App\Quiz::where('id', $id)->where('quiz_status', 'RESULTS_OUT')->count();

?>

<article>
    <div class="modal fade" id="userModalQuizResults" data-backdrop="static" data-keyboard="false" tabindex="-1"
         role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div style="padding:10px">
                    <center>
                        <img src="{{url('images/loader.gif')}}" id="loaderRex"/>
                    </center>
                    <div id="quizResxEditor"></div>
                </div>
            </div>
        </div>
    </div>
</article>

@if(count($attempts))

    @if($qf1 > 0)
        <div class="alert alert-warning">
            <h5><i class="fa fa-info-circle fa-1x"></i> Quiz is still in progress!!</h5>
        </div>
    @endif

    @if($qf2 > 0)
        <div class="alert alert-success">
            <h5><i class="fa fa-info-circle fa-1x"></i> You have published results!</h5>
        </div>
    @endif

    <table id="dataTable_reportx" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Score</th>
            <th>View Results</th>
        </tr>
        </thead>
        <tbody>
        <?php $c = 1; ?>
        @foreach($ranks as $k => $v)
            <?php $ur = "quiz/results/seenX/" . $id . "/" . $k; ?>
            <tr>
                <td>{{$c}}</td>
                <td>{{\App\User::find($k)->name}} ( {{\App\User::find($k)->email}} )</td>
                <td>{{$v}} / {{$answers}} </td>
                <td>
                    <button class="btn btn-primary btn-sm viewResultx" data-toggle="modal" route="{{url($ur)}}"
                            data-target="#userModalQuizResults">View Results
                    </button>
                </td>
            </tr>
            <?php $c++; ?>
        @endforeach

        </tbody>
    </table>

    <table id="printResultX" class="table table-striped table-bordered" style="display: none">
        <thead>
        <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Score</th>
        </tr>
        </thead>
        <tbody>
        <?php $c = 1; ?>
        @foreach($ranks as $k => $v)
            <?php $ur = "quiz/results/seenX/" . $id . "/" . $k; ?>
            <tr>
                <td>{{$c}}</td>
                <td>{{\App\User::find($k)->name}} ( {{\App\User::find($k)->email}} )</td>
                <td>{{$v}} / {{$answers}} </td>
            </tr>
            <?php $c++; ?>
        @endforeach

        </tbody>
    </table>

    <hr/>


    @if($qf > 0)
        <button route="{{route('quiz.publish.results', $id)}}" id="publishResx" class="btn btn-success btn-sm"><i
                    class="fa fa-check"></i> Publish Results Now
        </button>

        <button onclick="$('#printResultX').css('display', ''); printJS('printResultX', 'html'); $('#printResultX').css('display', 'none');"
                class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Print Results
        </button>
    @else

        <button onclick="$('#printResultX').css('display', ''); printJS('printResultX', 'html'); $('#printResultX').css('display', 'none');"
                class="btn btn-warning btn-sm"><i class="fa fa-print"></i> Print Results
        </button>
    @endif

@else

    <div class="alert alert-danger"><i class="fa fa-ban"></i> No one attempt on this quiz</div>

@endif


<script src="{{url('js/jquery.min.js')}}"></script>
<script src="{{url('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{url('js/matchHeight.min.js')}}"></script>
<script src="{{url('js/nprogress.min.js')}}"></script>
<script src="{{url('js/custom.min.js')}}"></script>

<script src="{{url('js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{url('js/dataTables.buttons.min.js')}}"></script>
<script src="{{url('js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{url('js/buttons.html5.min.js')}}"></script>
<script src="{{url('js/buttons.flash.min.js')}}"></script>
<script src="{{url('js/buttons.print.min.js')}}"></script>

<script type="text/javascript">
    $(function () {

        $('body').on('click', '.viewResultx', function () {
            var route = $(this).attr('route');
            $('#loaderRex').show();
            $('#quizResxEditor').html('');
            $.get(route, function (res) {
                $('#loaderRex').hide();
                $('#quizResxEditor').html(res);
            });
        });

        $('body').on('click', '#publishResx', function () {
            var route = $(this).attr('route');
            swal({
                title: "You are about to publish quiz results!",
                text: "",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {

                var data = {
                    _token: '{{csrf_token()}}'
                }

                Biggo.talkToServer(route, data).then(function (res) {
                    //return;
                    window.location = '{{route("quiz.refresh")}}';
                });
            });
        });

        $('body').on('click', '#unpublishResx', function () {
            var route = $(this).attr('route');

            swal({
                title: "You are about to publish quiz results!",
                text: "",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            }, function () {

                var data = {
                    _token: '{{csrf_token()}}'
                }
                Biggo.talkToServer(route, data).then(function (res) {
                    //return;
                    window.location = '{{route("quiz.refresh")}}';
                });
            });
        });

        $('#dataTable_reportx').DataTable();
    });
</script>


<script type="text/javascript">
    !function (e, t) {
        "object" == typeof exports && "object" == typeof module ? module.exports = t() : "function" == typeof define && define.amd ? define([], t) : "object" == typeof exports ? exports.printJS = t() : e.printJS = t()
    }(window, function () {
        return function (e) {
            var t = {};

            function n(r) {
                if (t[r]) return t[r].exports;
                var o = t[r] = {i: r, l: !1, exports: {}};
                return e[r].call(o.exports, o, o.exports, n), o.l = !0, o.exports
            }

            return n.m = e, n.c = t, n.d = function (e, t, r) {
                n.o(e, t) || Object.defineProperty(e, t, {enumerable: !0, get: r})
            }, n.r = function (e) {
                "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {value: "Module"}), Object.defineProperty(e, "__esModule", {value: !0})
            }, n.t = function (e, t) {
                if (1 & t && (e = n(e)), 8 & t) return e;
                if (4 & t && "object" == typeof e && e && e.__esModule) return e;
                var r = Object.create(null);
                if (n.r(r), Object.defineProperty(r, "default", {
                    enumerable: !0,
                    value: e
                }), 2 & t && "string" != typeof e) for (var o in e) n.d(r, o, function (t) {
                    return e[t]
                }.bind(null, o));
                return r
            }, n.n = function (e) {
                var t = e && e.__esModule ? function () {
                    return e.default
                } : function () {
                    return e
                };
                return n.d(t, "a", t), t
            }, n.o = function (e, t) {
                return Object.prototype.hasOwnProperty.call(e, t)
            }, n.p = "", n(n.s = 4)
        }([function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = i(n(2)), o = i(n(3));

            function i(e) {
                return e && e.__esModule ? e : {default: e}
            }

            var a = {
                send: function (e, t) {
                    document.getElementsByTagName("body")[0].appendChild(t);
                    var n = document.getElementById(e.frameId);
                    "pdf" === e.type && (r.default.isIE() || r.default.isEdge()) ? n.setAttribute("onload", d(n, e)) : t.onload = function () {
                        if ("pdf" === e.type) d(n, e); else {
                            var t = n.contentWindow || n.contentDocument;
                            if (t.document && (t = t.document), t.body.innerHTML = e.htmlData, "pdf" !== e.type && null !== e.style) {
                                var r = document.createElement("style");
                                r.innerHTML = e.style, t.head.appendChild(r)
                            }
                            "image" === e.type ? function (e, t) {
                                var n = [];
                                return t.printable.forEach(function (t, r) {
                                    return n.push(function (e, t) {
                                        return new Promise(function (n) {
                                            !function r() {
                                                var o = e ? e.getElementById("printableImage" + t) : null;
                                                o && void 0 !== o.naturalWidth && 0 !== o.naturalWidth ? n() : setTimeout(r, 500)
                                            }()
                                        })
                                    }(e, r))
                                }), Promise.all(n)
                            }(t, e).then(function () {
                                d(n, e)
                            }) : d(n, e)
                        }
                    }
                }
            };

            function d(e, t) {
                try {
                    !function (e, t) {
                        if (e.focus(), r.default.isEdge() || r.default.isIE()) try {
                            e.contentWindow.document.execCommand("print", !1, null)
                        } catch (t) {
                            e.contentWindow.print()
                        } else e.contentWindow.print()
                    }(e)
                } catch (e) {
                    t.onError(e)
                } finally {
                    !function (e) {
                        if (e.showModal && o.default.close(), e.onLoadingEnd && e.onLoadingEnd(), (e.showModal || e.onLoadingStart) && window.URL.revokeObjectURL(e.printable), e.onPrintDialogClose) {
                            var t = "mouseover";
                            (r.default.isChrome() || r.default.isFirefox()) && (t = "focus"), window.addEventListener(t, function n() {
                                window.removeEventListener(t, n), e.onPrintDialogClose()
                            })
                        }
                    }(t)
                }
            }

            t.default = a
        }, function (e, t, n) {
            "use strict";

            function r(e, t) {
                var n = "", r = (document.defaultView || window).getComputedStyle(e, "");
                return Object.keys(r).map(function (e) {
                    (-1 !== t.targetStyles.indexOf("*") || -1 !== t.targetStyle.indexOf(r[e]) || function (e, t) {
                        for (var n = 0; n < e.length; n++) if (-1 !== t.indexOf(e[n])) return !0;
                        return !1
                    }(t.targetStyles, r[e])) && r.getPropertyValue(r[e]) && (n += r[e] + ":" + r.getPropertyValue(r[e]) + ";")
                }), n += "max-width: " + t.maxWidth + "px !important;" + t.font_size + " !important;"
            }

            Object.defineProperty(t, "__esModule", {value: !0}), t.addWrapper = function (e, t) {
                return '<div style="font-family:' + t.font + " !important; font-size: " + t.font_size + ' !important; width:100%;">' + e + "</div>"
            }, t.capitalizePrint = function (e) {
                return e.charAt(0).toUpperCase() + e.slice(1)
            }, t.collectStyles = r, t.loopNodesCollectStyles = function e(t, n) {
                for (var o = 0; o < t.length; o++) {
                    var i = t[o];
                    if (-1 === n.ignoreElements.indexOf(i.getAttribute("id"))) {
                        var a = i.tagName;
                        if ("INPUT" === a || "TEXTAREA" === a || "SELECT" === a) {
                            var d = r(i, n), l = i.parentNode,
                                s = "SELECT" === a ? document.createTextNode(i.options[i.selectedIndex].text) : document.createTextNode(i.value),
                                u = document.createElement("div");
                            u.appendChild(s), u.setAttribute("style", d), l.appendChild(u), l.removeChild(i)
                        } else i.setAttribute("style", r(i, n));
                        var c = i.children;
                        c && c.length && e(c, n)
                    } else i.parentNode.removeChild(i)
                }
            }, t.addHeader = function (e, t, n) {
                var r = document.createElement("h1"), o = document.createTextNode(t);
                r.appendChild(o), r.setAttribute("style", n), e.insertBefore(r, e.childNodes[0])
            }
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = {
                isFirefox: function () {
                    return "undefined" != typeof InstallTrigger
                }, isIE: function () {
                    return -1 !== navigator.userAgent.indexOf("MSIE") || !!document.documentMode
                }, isEdge: function () {
                    return !r.isIE() && !!window.StyleMedia
                }, isChrome: function () {
                    return !!window.chrome && !!window.chrome.webstore
                }, isSafari: function () {
                    return Object.prototype.toString.call(window.HTMLElement).indexOf("Constructor") > 0 || -1 !== navigator.userAgent.toLowerCase().indexOf("safari")
                }
            };
            t.default = r
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = {
                show: function (e) {
                    var t = document.createElement("div");
                    t.setAttribute("style", "font-family:sans-serif; display:table; text-align:center; font-weight:300; font-size:30px; left:0; top:0;position:fixed; z-index: 9990;color: #0460B5; width: 100%; height: 100%; background-color:rgba(255,255,255,.9);transition: opacity .3s ease;"), t.setAttribute("id", "printJS-Modal");
                    var n = document.createElement("div");
                    n.setAttribute("style", "display:table-cell; vertical-align:middle; padding-bottom:100px;");
                    var o = document.createElement("div");
                    o.setAttribute("class", "printClose"), o.setAttribute("id", "printClose"), n.appendChild(o);
                    var i = document.createElement("span");
                    i.setAttribute("class", "printSpinner"), n.appendChild(i);
                    var a = document.createTextNode(e.modalMessage);
                    n.appendChild(a), t.appendChild(n), document.getElementsByTagName("body")[0].appendChild(t), document.getElementById("printClose").addEventListener("click", function () {
                        r.close()
                    })
                }, close: function () {
                    var e = document.getElementById("printJS-Modal");
                    e.parentNode.removeChild(e)
                }
            };
            t.default = r
        }, function (e, t, n) {
            e.exports = n(5)
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0}), n(6);
            var r = function (e) {
                return e && e.__esModule ? e : {default: e}
            }(n(8)).default.init;
            "undefined" != typeof window && (window.printJS = r), t.default = r
        }, function (e, t, n) {
        }, , function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
                return typeof e
            } : function (e) {
                return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
            }, o = u(n(2)), i = u(n(3)), a = u(n(9)), d = u(n(10)), l = u(n(11)), s = u(n(12));

            function u(e) {
                return e && e.__esModule ? e : {default: e}
            }

            var c = ["pdf", "html", "image", "json"];
            t.default = {
                init: function () {
                    var e = {
                        printable: null,
                        fallbackPrintable: null,
                        type: "pdf",
                        header: null,
                        headerStyle: "font-weight: 300;",
                        maxWidth: 800,
                        font: "TimesNewRoman",
                        font_size: "12pt",
                        honorMarginPadding: !0,
                        honorColor: !1,
                        properties: null,
                        gridHeaderStyle: "font-weight: bold; padding: 5px; border: 1px solid #dddddd;",
                        gridStyle: "border: 1px solid lightgray; margin-bottom: -1px;",
                        showModal: !1,
                        onError: function (e) {
                            throw e
                        },
                        onLoadingStart: null,
                        onLoadingEnd: null,
                        onPrintDialogClose: null,
                        onPdfOpen: null,
                        modalMessage: "Retrieving Document...",
                        frameId: "printJS",
                        htmlData: "",
                        documentTitle: "Document",
                        targetStyle: ["clear", "display", "width", "min-width", "height", "min-height", "max-height"],
                        targetStyles: ["border", "box", "break", "text-decoration"],
                        ignoreElements: [],
                        imageStyle: "width:100%;",
                        repeatTableHeader: !0,
                        css: null,
                        style: null,
                        scanStyles: !0
                    }, t = arguments[0];
                    if (void 0 === t) throw new Error("printJS expects at least 1 attribute.");
                    switch (void 0 === t ? "undefined" : r(t)) {
                        case"string":
                            e.printable = encodeURI(t), e.fallbackPrintable = e.printable, e.type = arguments[1] || e.type;
                            break;
                        case"object":
                            e.printable = t.printable, e.fallbackPrintable = void 0 !== t.fallbackPrintable ? t.fallbackPrintable : e.printable, e.type = void 0 !== t.type ? t.type : e.type, e.frameId = void 0 !== t.frameId ? t.frameId : e.frameId, e.header = void 0 !== t.header ? t.header : e.header, e.headerStyle = void 0 !== t.headerStyle ? t.headerStyle : e.headerStyle, e.maxWidth = void 0 !== t.maxWidth ? t.maxWidth : e.maxWidth, e.font = void 0 !== t.font ? t.font : e.font, e.font_size = void 0 !== t.font_size ? t.font_size : e.font_size, e.honorMarginPadding = void 0 !== t.honorMarginPadding ? t.honorMarginPadding : e.honorMarginPadding, e.properties = void 0 !== t.properties ? t.properties : e.properties, e.gridHeaderStyle = void 0 !== t.gridHeaderStyle ? t.gridHeaderStyle : e.gridHeaderStyle, e.gridStyle = void 0 !== t.gridStyle ? t.gridStyle : e.gridStyle, e.showModal = void 0 !== t.showModal ? t.showModal : e.showModal, e.onError = void 0 !== t.onError ? t.onError : e.onError, e.onLoadingStart = void 0 !== t.onLoadingStart ? t.onLoadingStart : e.onLoadingStart, e.onLoadingEnd = void 0 !== t.onLoadingEnd ? t.onLoadingEnd : e.onLoadingEnd, e.onPrintDialogClose = void 0 !== t.onPrintDialogClose ? t.onPrintDialogClose : e.onPrintDialogClose, e.onPdfOpen = void 0 !== t.onPdfOpen ? t.onPdfOpen : e.onPdfOpen, e.modalMessage = void 0 !== t.modalMessage ? t.modalMessage : e.modalMessage, e.documentTitle = void 0 !== t.documentTitle ? t.documentTitle : e.documentTitle, e.targetStyle = void 0 !== t.targetStyle ? t.targetStyle : e.targetStyle, e.targetStyles = void 0 !== t.targetStyles ? t.targetStyles : e.targetStyles, e.ignoreElements = void 0 !== t.ignoreElements ? t.ignoreElements : e.ignoreElements, e.imageStyle = void 0 !== t.imageStyle ? t.imageStyle : e.imageStyle, e.repeatTableHeader = void 0 !== t.repeatTableHeader ? t.repeatTableHeader : e.repeatTableHeader, e.css = void 0 !== t.css ? t.css : e.css, e.style = void 0 !== t.style ? t.style : e.style, e.scanStyles = void 0 !== t.scanStyles ? t.scanStyles : e.scanStyles;
                            break;
                        default:
                            throw new Error('Unexpected argument type! Expected "string" or "object", got ' + (void 0 === t ? "undefined" : r(t)))
                    }
                    if (!e.printable) throw new Error("Missing printable information.");
                    if (!e.type || "string" != typeof e.type || -1 === c.indexOf(e.type.toLowerCase())) throw new Error("Invalid print type. Available types are: pdf, html, image and json.");
                    e.showModal && i.default.show(e), e.onLoadingStart && e.onLoadingStart();
                    var n = document.getElementById(e.frameId);
                    n && n.parentNode.removeChild(n);
                    var u = void 0;
                    switch ((u = document.createElement("iframe")).setAttribute("style", "visibility: hidden; height: 0; width: 0; position: absolute;"), u.setAttribute("id", e.frameId), "pdf" !== e.type && (u.srcdoc = "<html><head><title>" + e.documentTitle + "</title>", null !== e.css && (Array.isArray(e.css) || (e.css = [e.css]), e.css.forEach(function (e) {
                        u.srcdoc += '<link rel="stylesheet" href="' + e + '">'
                    })), u.srcdoc += "</head><body></body></html>"), e.type) {
                        case"pdf":
                            if (o.default.isFirefox() || o.default.isEdge() || o.default.isIE()) try {
                                console.info("PrintJS currently doesn't support PDF printing in Firefox, Internet Explorer and Edge."), window.open(e.fallbackPrintable, "_blank").focus(), e.onPdfOpen && e.onPdfOpen()
                            } catch (t) {
                                e.onError(t)
                            } finally {
                                e.showModal && i.default.close(), e.onLoadingEnd && e.onLoadingEnd()
                            } else a.default.print(e, u);
                            break;
                        case"image":
                            l.default.print(e, u);
                            break;
                        case"html":
                            d.default.print(e, u);
                            break;
                        case"json":
                            s.default.print(e, u)
                    }
                }
            }
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = function (e) {
                return e && e.__esModule ? e : {default: e}
            }(n(0));

            function o(e, t) {
                t.setAttribute("src", e.printable), r.default.send(e, t)
            }

            t.default = {
                print: function (e, t) {
                    if (e.printable = /^(blob|http)/i.test(e.printable) ? e.printable : window.location.origin + ("/" !== e.printable.charAt(0) ? "/" + e.printable : e.printable), e.showModal || e.onLoadingStart) {
                        var n = new window.XMLHttpRequest;
                        n.responseType = "arraybuffer", n.addEventListener("load", function () {
                            var r = new window.Blob([n.response], {type: "application/pdf"});
                            r = window.URL.createObjectURL(r), e.printable = r, o(e, t)
                        }), n.open("GET", e.printable, !0), n.send()
                    } else o(e, t)
                }
            }
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = n(1), o = function (e) {
                return e && e.__esModule ? e : {default: e}
            }(n(0));
            t.default = {
                print: function (e, t) {
                    var n = document.getElementById(e.printable);
                    if (!n) return window.console.error("Invalid HTML element id: " + e.printable), !1;
                    var i = document.createElement("div");
                    if (i.appendChild(n.cloneNode(!0)), i.setAttribute("style", "height:0; overflow:hidden;"), i.setAttribute("id", "printJS-html"), n.parentNode.appendChild(i), i = document.getElementById("printJS-html"), !0 === e.scanStyles) {
                        e.honorMarginPadding && e.targetStyles.push("margin", "padding"), e.honorColor && e.targetStyles.push("color"), i.setAttribute("style", (0, r.collectStyles)(i, e) + "margin:0 !important;");
                        var a = i.children;
                        (0, r.loopNodesCollectStyles)(a, e)
                    }
                    e.header && (0, r.addHeader)(i, e.header, e.headerStyle), i.parentNode.removeChild(i), e.htmlData = (0, r.addWrapper)(i.innerHTML, e), o.default.send(e, t)
                }
            }
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = n(1), o = function (e) {
                return e && e.__esModule ? e : {default: e}
            }(n(0));
            t.default = {
                print: function (e, t) {
                    e.printable.constructor !== Array && (e.printable = [e.printable]);
                    var n = document.createElement("div");
                    n.setAttribute("style", "width:100%"), function (e, t) {
                        var n = [];
                        return t.printable.forEach(function (r, o) {
                            var i = document.createElement("img");
                            i.src = r, n.push(function (e, t, n, r) {
                                return new Promise(function (o) {
                                    n.onload = function () {
                                        var i = document.createElement("div");
                                        i.setAttribute("style", t.imageStyle), n.setAttribute("style", "width:100%;"), n.setAttribute("id", "printableImage" + r), i.appendChild(n), e.appendChild(i), o()
                                    }
                                })
                            }(e, t, i, o))
                        }), Promise.all(n)
                    }(n, e).then(function () {
                        e.header && (0, r.addHeader)(n, e.header, e.headerStyle), e.htmlData = n.outerHTML, o.default.send(e, t)
                    })
                }
            }
        }, function (e, t, n) {
            "use strict";
            Object.defineProperty(t, "__esModule", {value: !0});
            var r = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (e) {
                return typeof e
            } : function (e) {
                return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
            }, o = n(1), i = function (e) {
                return e && e.__esModule ? e : {default: e}
            }(n(0));
            t.default = {
                print: function (e, t) {
                    if ("object" !== r(e.printable)) throw new Error("Invalid javascript data object (JSON).");
                    if ("boolean" != typeof e.repeatTableHeader) throw new Error("Invalid value for repeatTableHeader attribute (JSON).");
                    if (!e.properties || !Array.isArray(e.properties)) throw new Error("Invalid properties array for your JSON data.");
                    e.properties = e.properties.map(function (t) {
                        return {
                            field: "object" === (void 0 === t ? "undefined" : r(t)) ? t.field : t,
                            displayName: "object" === (void 0 === t ? "undefined" : r(t)) ? t.displayName : t,
                            columnSize: "object" === (void 0 === t ? "undefined" : r(t)) && (t.columnSize, 1) ? t.columnSize : 100 / e.properties.length + "%;"
                        }
                    });
                    var n = "";
                    e.header && (n += '<h1 style="' + e.headerStyle + '">' + e.header + "</h1>"), n += function (e) {
                        var t = e.printable, n = e.properties,
                            r = '<table style="border-collapse: collapse; width: 100%;">';
                        e.repeatTableHeader && (r += "<thead>");
                        r += "<tr>";
                        for (var i = 0; i < n.length; i++) r += '<th style="width:' + n[i].columnSize + ";" + e.gridHeaderStyle + '">' + (0, o.capitalizePrint)(n[i].displayName) + "</th>";
                        r += "</tr>", e.repeatTableHeader && (r += "</thead>");
                        r += "<tbody>";
                        for (var a = 0; a < t.length; a++) {
                            r += "<tr>";
                            for (var d = 0; d < n.length; d++) {
                                var l = t[a], s = n[d].field.split(".");
                                if (s.length > 1) for (var u = 0; u < s.length; u++) l = l[s[u]]; else l = l[n[d].field];
                                r += '<td style="width:' + n[d].columnSize + e.gridStyle + '">' + l + "</td>"
                            }
                            r += "</tr>"
                        }
                        return r += "</tbody></table>"
                    }(e), e.htmlData = (0, o.addWrapper)(n, e), i.default.send(e, t)
                }
            }
        }]).default
    });
</script>