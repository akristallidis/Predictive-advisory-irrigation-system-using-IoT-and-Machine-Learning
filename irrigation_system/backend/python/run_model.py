#!/usr/bin/env python
import pandas as pd
import numpy as np
import pickle
import requests

cropId = pd.read_csv('lastDataRowCSV.csv').iloc[:, 0].values
data = pd.read_csv('lastDataRowCSV.csv').iloc[:, 1:6].values
output = pd.read_csv('cropDataCSV.csv').iloc[:, 7].values
minval = np.min(output[np.nonzero(output)])
print(data)
filename = 'model.sav'
loaded_model = pickle.load(open(filename, 'rb'))
result = loaded_model.predict(data)

if result<minval:
    result = 0.0

URL = "******path******/irrigation/backend/API/advice/adviceManagement.php"
data = {'cropId': cropId, 'liters': result}
r = requests.get(url = URL, params = data)
print(r)
print(result)