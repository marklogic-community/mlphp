/*
Copyright 2002-2012 MarkLogic Corporation.  All Rights Reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

function initialize()
{
    var mapOptions,
        map,
        marker,
        markers = [],
        content = [],
        infowindows = [],
        infowindow = null,
        loc,
        conf,
        markerIndex,
        myLatlng,
        padding,
        maxDim,
        footer,
        wimg,
        wcont,
        himg,
        hcont,
        index;

    mapOptions = {
        center: new google.maps.LatLng(17, -135),
        zoom: 3,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

    for (loc in locations) {
        if (locations.hasOwnProperty(loc)) {

            myLatlng = new google.maps.LatLng(locations[loc].latitude, locations[loc].longitude);

            // dimensions for info window
            padding = 20;
            maxDim = 300;
            footer = 25;

            if (locations[loc].width > locations[loc].height) {
                wimg = maxDim;
                wcont = wimg + padding;
                himg = (maxDim / locations[loc].width) * locations[loc].height;
                hcont = himg + padding + footer;
            } else {
                himg = maxDim;
                hcont = himg + padding + footer;
                wimg = (maxDim / locations[loc].height) * locations[loc].width;
                wcont = wimg + padding;
            }

            marker = new google.maps.Marker({
                icon: 'images/photo_icon.png',
                position: myLatlng,
                map: map,
                title: loc,
                html: '<div id="info-' + loc.replace('.', '-') + '" style="height: ' + hcont + 'px; width: ' + wcont + 'px">' +
                '<img class="info-image" height="' + himg + '" src="image.php?uri=' + loc + '"><div class="info-footer" rel="' +
                markers.length + '">' + locations[loc].filename + ' <span class="delete" rel="' + loc +
                '">Delete</span></div></div>'
            });

            markers.push(marker);
            content.push(loc);

        }

    }

    function deleteDocument(uri)
    {
        $.ajax({
            url: 'delete.php?uri=' + uri,
            type: "GET",
            success: function (resp) { }
        });
    }

    function updateTotal()
    {
        var count = 0,
            index;
        for(index in markers) {
            if (markers[index] !== undefined) {
                count++;
            }
        }
        $('#total').html(count);
    }

    for (index in markers) {

        marker = markers[index];
        infowindow = new google.maps.InfoWindow({ content: content[index] });
        infowindows[index] = infowindow;

        google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent(this.html);
            infowindow.open(map, this);
            $('#info-' + this.title.replace('.', '-') + ' .delete').on('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                conf = confirm('Delete ' + $(this).attr('rel') + ' from the map and database?');
                if (conf) {
                    markerIndex = $(this.parentElement).attr('rel');
                    deleteDocument($(this).attr('rel'));
                    infowindow.close();
                    markers[markerIndex].setMap(null);
                    delete(markers[markerIndex]);
                    updateTotal();
                }
            });
        });
    }

}