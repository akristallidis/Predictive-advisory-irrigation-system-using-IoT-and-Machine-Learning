#!/usr/bin/env python
import pandas as pd
import numpy as np
import pickle
import matplotlib.pyplot as plt
import PIL
import pydot
import warnings
from sklearn import tree
from glob import glob
from sklearn.ensemble import RandomForestClassifier
from sklearn.tree import export_graphviz
from IPython.display import Image
from sklearn.tree import plot_tree
from dtreeviz.trees import dtreeviz
from sklearn.metrics import accuracy_score
warnings.filterwarnings("ignore")
plt.rcParams.update({'figure.figsize': (12.0, 8.0)})
plt.rcParams.update({'font.size': 14})

dataset = pd.read_csv('cropDataCSV.csv')
print(dataset.head)

X = dataset.iloc[:, 2:7].values
y = dataset.iloc[:, 7].values

from sklearn.model_selection import train_test_split
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=0)

from sklearn.ensemble import RandomForestRegressor
regressor = RandomForestRegressor(n_estimators=5, random_state=0, bootstrap=True, criterion='mae')
regressor.fit(X_train, y_train)
y_pred = regressor.predict(X_test)

print(X.shape, y.shape)

from sklearn import metrics
print('Mean Absolute Error:', metrics.mean_absolute_error(y_test, y_pred))
print('Mean Squared Error:', metrics.mean_squared_error(y_test, y_pred))
print('Root Mean Squared Error:', np.sqrt(metrics.mean_squared_error(y_test, y_pred)))
fig=plt.figure(figsize=(20,20))
plot_tree(regressor.estimators_[0], feature_names=X, filled=True)
fig.savefig('figure_name.png')

filename = 'model.sav'
pickle.dump(regressor, open(filename, 'wb'))