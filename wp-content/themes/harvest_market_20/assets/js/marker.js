
    /* global google */
    var createHTMLMapMarker = function createHTMLMapMarker(_ref) {
      var _ref$OverlayView = _ref.OverlayView,
          OverlayView = _ref$OverlayView === void 0 ? google.maps.OverlayView : _ref$OverlayView,
          args = _objectWithoutProperties(_ref, _excluded);
  
      var HTMLMapMarker = /*#__PURE__*/function (_OverlayView) {
        _inherits(HTMLMapMarker, _OverlayView);
  
        var _super = _createSuper(HTMLMapMarker);
  
        function HTMLMapMarker() {
          var _this;
  
          _classCallCheck(this, HTMLMapMarker);
  
          _this = _super.call(this);
          _this.latlng = args.latlng;
          _this.html = args.html;
  
          _this.setMap(args.map);
  
          return _this;
        }
  
        _createClass(HTMLMapMarker, [{
          key: "createDiv",
          value: function createDiv() {
            var _this2 = this;
  
            this.div = document.createElement("div");
            this.div.style.position = "absolute";
  
            if (this.html) {
              this.div.innerHTML = this.html;
            }
  
            google.maps.event.addDomListener(this.div, "click", function (event) {
              google.maps.event.trigger(_this2, "click");
            });
          }
        }, {
          key: "appendDivToOverlay",
          value: function appendDivToOverlay() {
            var panes = this.getPanes();
            panes.overlayImage.appendChild(this.div);
          }
        }, {
          key: "positionDiv",
          value: function positionDiv() {
            var point = this.getProjection().fromLatLngToDivPixel(this.latlng);
            var offset = 25;
  
            if (point) {
              this.div.style.left = "".concat(point.x - offset, "px");
              this.div.style.top = "".concat(point.y - offset, "px");
            }
          }
        }, {
          key: "draw",
          value: function draw() {
            if (!this.div) {
              this.createDiv();
              this.appendDivToOverlay();
            }
  
            this.positionDiv();
          }
        }, {
          key: "remove",
          value: function remove() {
            if (this.div) {
              this.div.parentNode.removeChild(this.div);
              this.div = null;
            }
          }
        }, {
          key: "getPosition",
          value: function getPosition() {
            return this.latlng;
          }
        }, {
          key: "getDraggable",
          value: function getDraggable() {
            return false;
          }
        }]);
  
        return HTMLMapMarker;
      }(OverlayView);
  
      return new HTMLMapMarker();
    }; 