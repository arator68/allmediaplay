/* $Id: avreloaded-uncompressed.js 994 2008-06-27 03:55:33Z Fritz Elfert $ */
if (typeof (allvideos) == "undefined") {
    var allvideos = new Object();
    allvideos.APIs = new Array()
}
function getUpdate(D, C, B, A) {
    if (A == "null") {
        return
    }
    allvideos.APIs.each(function(E) {
        if (E._pid == A) {
            E._plCB(D, C, B)
        }
    })
}
allvideos.API = function(B) {
    var A = null;
    allvideos.APIs.each(function(C) {
        if (C._pid == B) {
            A = C
        }
    });
    if (A != null) {
        return A
    }
    this._pid = B;
    this._player = null;
    this._item = null;
    this._load = null;
    this._width = null;
    this._height = null;
    this._state = null;
    this._elapsed = null;
    this._remaining = null;
    this._volume = null;
    this._plCB = function(E, D, C) {
        switch (E) {
            case"item":
                this._item = D;
                break;
            case"load":
                this._load = D;
                break;
            case"size":
                this._width = D;
                this._height = C;
                break;
            case"state":
                this._state = D;
                break;
            case"time":
                this._elapsed = D;
                this._remaining = C;
                break;
            case"volume":
                this._volume = D;
                break
            }
    };
    this.p = function() {
        if (this._player == null) {
            if (navigator.appName.indexOf("Microsoft") != -1) {
                this._player = window[this._pid]
            } else {
                this._player = document[this._pid]
            }
        }
        return this._player
    };
    this.sendEvent = function(D, C) {
        this.p().sendEvent(D, C)
    };
    this.loadFile = function(C) {
        this.p().loadFile(C)
    };
    this.addItem = function(D, C) {
        this.p().addItem(D, C)
    };
    this.removeItem = function(C) {
        this.p().removeItem(C)
    };
    this.togglePause = function() {
        this.sendEvent("playpause")
    };
    this.next = function() {
        this.sendEvent("next")
    };
    this.prev = function() {
        this.sendEvent("prev")
    };
    this.scrub = function(C) {
        this.sendEvent("scrub", (this._elapsed ? this._elapsed : 0) + C)
    };
    this.volume = function(C) {
        this.sendEvent("volume", (this._volume ? this._volume : 0) + C)
    };
    this.getLength = function() {
        return this.p().getLength()
    };
    this.navItem = function(C) {
        this.sendEvent("getlink", (C == null) ? 0 : C)
    };
    this.play = function(C) {
        this.sendEvent("playitem", (C == null) ? 0 : C)
    };
    this.stop = function() {
        this.sendEvent("stop")
    };
    allvideos.APIs.push(this)
};
function AvrPopup(E, F, C) {
    new Event(E).stop();
    var B = $("avrpopup_" + F);
    if (B) {
        if (!C) {
            C = "lightbox"
        }
        var D = Json.evaluate(B.getProperty("title"));
        if (C == "lightbox") {
            D.url = decodeURIComponent(D.url);
            if (typeof (window.opera) != "undefined") {
                D.size.y += 4
            }
            D.onClose = function() {
                var G = $$("#sbox-content iframe");
                if (G && (G.length > 0)) {
                    G[0].src = "about:blank"
                }
            };
            SqueezeBox.fromElement(B, D)
        } else {
            var A = window.open(decodeURIComponent(D.url), "avrpopup" + F, "status=no,toolbar=no,scrollbars=no,titlebar=no,menubar=no,resizable=no,width=" + D.size.x + ",height=" + D.size.y + ",directories=no,location=no");
            A.focus()
        }
    }
}
;