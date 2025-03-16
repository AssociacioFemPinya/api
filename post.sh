#!/bin/bash

curl -X PUT "http://localhost:8005/api-fempinya/mobile_events/26" \
     -H "Authorization: Bearer suOrs6kIjDLaGr7d09rnA7NuZ45hht5J5DqSCkdQ9a923540" \
     -H "Accept: application/json" \
     -H "Content-Type: application/json" \
     -d '{
           "idEvent": 26,
           "title": "Assaig de Dimecres",
           "startDate": "2025-09-27 22:54:23",
           "endDate": "2025-09-16 23:53:59",
           "address": "Rúa Espinal, 8, 6º C, Badalona, Tarragona",
           "status": "accepted",
           "type": "training",
           "companions": 1,
           "tags": [
             {
               "id": 18,
               "name": "A la mateixa provincia",
               "isEnabled": false
             }
           ],
           "comment": ""
         }' | jq