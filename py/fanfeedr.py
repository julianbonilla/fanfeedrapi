#!/usr/bin/python
# -*- coding: utf-8 -*-
###################################################################
# A simple python client for making calls to the FanFeedr API.
# For more information, please see http://developer.fanfeedr.com/
# @author Lucas Hrabovsky <lucas@fanfeedr.com>
# December 26th, 2009
###################################################################

import os
import sys
import json
import urllib2
import urllib

class FanFeedr(object):
    def __init__(self, api_key, tier='basic', gateway_url='http://api.fanfeedr.com/'):
        '''
        Basic constructor.
        @param string api_key Your API key for the tier you are requesting to.
        @param string tier (Optional) The API tier you have access to (basic, daily, gold, or platinum).
        @param string gateway_url (Optional) Simple placeholder for supporting multiple gateways (ie for staging).
        '''
        self.tier = tier
        self.api_key = api_key
        self.gateway_url = gateway_url

    def __fetch(self, service, method, params=None):
        '''
        Private method for fetching from HTTP and decoding JSON.
        @param string service URL namespace for service (ie basic, gaming, user, etc).  Placeholder for now as all are basic.
        @param string method The API method to call.
        @param object params (Optional) Additional params to send along with the request.
        '''
        param_string = ''
        if params!=None:
            param_string = urllib.urlencode(params)
        url = self.gateway_url
        if self.tier!= '':
            url += self.tier+'/'
        url += method+'?format=json&appid='+self.api_key
        url += '&'+param_string
        c=urllib2.urlopen(url)
        contents = c.read()
        return json.read(contents)

    def suggest(self, q):
        '''
        This call provides typeahead suggestion for entity resource lookups, ie for the
        query red you recieve path and resource info for the Red Sox, Chris Redman, and
        Detriot Red Wings.
        Example: 
            http://api.fanfeedr.com/basic/suggest?appid=<your-basic-api-key>&format=json
        '''
         params = {
            'q' : q
        }
        return self.__fetch('basic', 'suggest', params)

    def all_scores(self, start='0',rows='20'):
        '''
        A news feed of all the most recent scores for all teams.
        Example: 
            http://api.fanfeedr.com/basic/all_scores?appid=<your-basic-api-key>&format=json
        '''
         params = {
            'start' : start,
            'rows' : rows
        }
        return self.__fetch('basic', 'all_scores', params)

    def scores(self, resource,start='0',rows='20'):
        '''
        Call to return the scores for a given league resource.
        Example: 
            http://api.fanfeedr.com/basic/scores?appid=<your-basic-api-key>&format=json&resource=team://nfl/pittsburgh_steelers
        '''
         params = {
            'resource' : resource,
            'start' : start,
            'rows' : rows
        }
        return self.__fetch('basic', 'scores', params)

    def geo_feed(self, start='0',rows='20',lat='',long=''):
        '''
        Provides ta news feed based on teams found near a given geo location. This is
        tparticulary useful if you have access to geolocation data from ip tlookups or
        from an iPhone app.
        Example: 
            http://api.fanfeedr.com/basic/geo_feed?appid=<your-basic-api-key>&format=json
        '''
         params = {
            'start' : start,
            'rows' : rows,
            'lat' : lat,
            'long' : long
        }
        return self.__fetch('basic', 'geo_feed', params)

    def search(self, q,start='0',rows='20',filter='search',date='all-time',content_type='all'):
        '''
        Provides a news feed for a given query string.
        Example: 
            http://api.fanfeedr.com/basic/search?appid=<your-basic-api-key>&format=json&q=steelers
        '''
         params = {
            'q' : q,
            'start' : start,
            'rows' : rows,
            'filter' : filter,
            'date' : date,
            'content_type' : content_type
        }
        return self.__fetch('basic', 'search', params)

    def article(self, resource):
        '''
        Provides formatted article text for a given article resource
        Example: 
            http://api.fanfeedr.com/basic/article?appid=<your-basic-api-key>&format=json&resource=article://nhl/tuesdays_recaps/2009/06/02/gonchar_leads_confident_pens_past_wings_pittsburgh_4_detroit_2
        '''
         params = {
            'resource' : resource
        }
        return self.__fetch('basic', 'article', params)

    def recap(self, resource):
        '''
        Gets the recap of an event
        Example: 
            http://api.fanfeedr.com/basic/recap?appid=<your-basic-api-key>&format=json&resource=event://20091130nhl--nyrangers-0
        '''
         params = {
            'resource' : resource
        }
        return self.__fetch('basic', 'recap', params)

    def boxscore(self, resource):
        '''
        Gets the boxscore of an event
        Example: 
            http://api.fanfeedr.com/basic/boxscore?appid=<your-basic-api-key>&format=json&resource=event://20091130nhl--nyrangers-0
        '''
         params = {
            'resource' : resource
        }
        return self.__fetch('basic', 'boxscore', params)

    def schedule(self, resource):
        '''
        Gets the schedule for a team resource.
        Example: 
            http://api.fanfeedr.com/basic/schedule?appid=<your-basic-api-key>&format=json&resource=team://nhl/detroit_red_wings
        '''
         params = {
            'resource' : resource
        }
        return self.__fetch('basic', 'schedule', params)

    def resource_feed(self, resource):
        '''
        Gets a list of articles for a resource
        Example: 
            http://api.fanfeedr.com/basic/resource_feed?appid=<your-basic-api-key>&format=json&resource=team://nfl/pittsburgh_steelers
        '''
         params = {
            'resource' : resource
        }
        return self.__fetch('basic', 'resource_feed', params)

