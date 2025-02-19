withCredentials([file(credentialsId: 'kubeconfig', variable: 'KUBECONFIG')]) {
    sh '''
    commit_hash=$(cat commit_hash.txt)
    branch=$(cat branch.txt)

    if [ "$branch" = "dev" ]; then
        DEPLOYMENT="<dev/uat deployment>"
    elif [ "$branch" = "prod" ]; then
        DEPLOYMENT="<prod deployment>"
    else
        echo "Error: Unknown branch '$branch'. Skipping deployment."
        exit 1
    fi

    curl -LO "https://dl.k8s.io/release/$(curl -L -s https://dl.k8s.io/release/stable.txt)/bin/linux/amd64/kubectl"
    chmod +x kubectl

    ./kubectl --kubeconfig $KUBECONFIG set image deployment/$DEPLOYMENT <container>=sccity/<image>:$commit_hash -n <namespace>

    if [ $? -ne 0 ]; then
        echo "Error: Kubernetes update failed!"
        exit 1
    fi

    ./kubectl --kubeconfig $KUBECONFIG rollout status deployment/$DEPLOYMENT -n <namespace>

    if [ $? -ne 0 ]; then
        echo "Error: Kubernetes rollout failed!"
        exit 1
    fi
    '''
}